<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3'; // useForm eklenebilir ama axios ile devam ettik
import axios from 'axios';

// Emoji Picker
import EmojiPicker from 'vue3-emoji-picker';
import 'vue3-emoji-picker/css';

const props = defineProps({
    contacts: Array,
    currentUser: Object
});

// --- STATE YÖNETİMİ ---
const activeContact = ref(null);
const messages = ref([]);
const newMessage = ref('');
const messagesContainer = ref(null);
const searchQuery = ref('');
const isSidebarOpen = ref(true);

// Yeni Eklenen State'ler
const showEmojiPicker = ref(false);
const fileInput = ref(null);
const attachment = ref(null); // Seçilen dosya objesi
const attachmentPreview = ref(null); // Önizleme URL'i

// --- COMPUTED ---
const filteredContacts = computed(() => {
    if (!searchQuery.value) return props.contacts;
    return props.contacts.filter(contact => 
        contact.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// --- 1. KİŞİ SEÇME ---
const selectContact = async (contact) => {
    activeContact.value = contact;
    localStorage.setItem('lastActiveContactId', contact.id);
    
    // State temizliği
    messages.value = [];
    newMessage.value = '';
    attachment.value = null;
    attachmentPreview.value = null;

    try {
        const response = await axios.get(route('chat.messages', contact.id));
        messages.value = response.data;
        scrollToBottom();
        
        if (window.innerWidth < 768) isSidebarOpen.value = false;
    } catch (error) {
        console.error("Mesajlar yüklenemedi:", error);
    }
};

// --- 2. DOSYA YÜKLEME İŞLEMLERİ ---
const triggerFileSelect = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    attachment.value = file;

    // Resimse önizleme oluştur
    if (file.type.startsWith('image/')) {
        attachmentPreview.value = URL.createObjectURL(file);
    } else {
        attachmentPreview.value = null; // Dosya ikon olarak gösterilecek
    }
};

const removeAttachment = () => {
    attachment.value = null;
    attachmentPreview.value = null;
    if (fileInput.value) fileInput.value.value = ''; // Inputu resetle
};

// --- 3. EMOJI İŞLEMLERİ ---
const onSelectEmoji = (emoji) => {
    newMessage.value += emoji.i;
};
const toggleEmoji = () => {
    showEmojiPicker.value = !showEmojiPicker.value;
};

// --- 4. MESAJ GÖNDERME (FormData ile) ---
const sendMessage = async () => {
    if ((!newMessage.value.trim() && !attachment.value) || !activeContact.value) return;

    const receiverId = activeContact.value.id;
    
    // Optimistik UI (Geçici gösterim)
    // Not: Dosya varsa optimistik UI biraz karmaşıktır, burada basitleştirilmiş hali var.
    const tempMessage = {
        id: Date.now(),
        sender_id: props.currentUser.id,
        receiver_id: receiverId,
        message: newMessage.value,
        file_url: attachmentPreview.value, // Önizleme için
        file_type: attachment.value ? (attachment.value.type.startsWith('image/') ? 'image' : 'file') : null,
        file_name: attachment.value ? attachment.value.name : null,
        created_at: new Date().toISOString()
    };

    messages.value.push(tempMessage);
    scrollToBottom();

    // Verileri hazırla (FormData zorunlu çünkü dosya var)
    const formData = new FormData();
    formData.append('receiver_id', receiverId);
    formData.append('message', newMessage.value || ''); // Boş mesaj olabilir eğer dosya varsa
    if (attachment.value) {
        formData.append('file', attachment.value);
    }

    // Formu Temizle
    newMessage.value = '';
    showEmojiPicker.value = false;
    removeAttachment();

    try {
        // Backend'e gönder
        // Content-Type: multipart/form-data axios tarafından otomatik ayarlanır
        await axios.post(route('chat.send'), formData);
    } catch (error) {
        console.error("Mesaj gönderilemedi", error);
        // Hata yönetimi burada yapılabilir
    }
};

// --- 5. SOHBETİ TEMİZLEME (SİLME) ---
const clearChat = async () => {
    if (!activeContact.value) return;
    
    if (confirm(`${activeContact.value.name} ile olan tüm mesajları silmek istediğinize emin misiniz?`)) {
        try {
            // Laravel rotasının: Route::delete('/chat/{user}', ...) olduğunu varsayıyoruz
            await axios.delete(route('chat.clear', activeContact.value.id));
            messages.value = []; // Ekrandan temizle
        } catch (error) {
            console.error("Silme işlemi başarısız", error);
        }
    }
};

// --- 6. SCROLL & WEBSOCKET ---
const scrollToBottom = () => {
    nextTick(() => {
        if (messagesContainer.value) {
            messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
        }
    });
};

onMounted(async () => {
    if (window.Echo) {
        window.Echo.private(`chat.user.${props.currentUser.id}`)
            .listen('MessageSent', (e) => {
                if (activeContact.value && activeContact.value.id === e.message.sender_id) {
                    messages.value.push(e.message);
                    scrollToBottom();
                }
            });
    }

    const lastContactId = localStorage.getItem('lastActiveContactId');
    if (lastContactId) {
        const found = props.contacts.find(c => c.id == lastContactId);
        if (found) await selectContact(found);
    }
});

onUnmounted(() => {
    if (window.Echo) {
        window.Echo.leave(`chat.user.${props.currentUser.id}`);
    }
});
</script>

<template>
    <Head title="Mesajlar" />

    <div class="chat-layout bg-grey-lighten-4">
        
        <div class="chat-sidebar bg-white elevation-1 flex-column" :class="{ 'd-none d-md-flex': !isSidebarOpen, 'd-flex': isSidebarOpen }">
            <div class="sidebar-header px-5 py-4 border-b">
                <div class="d-flex align-center justify-space-between mb-4">
                    <h2 class="text-h6 font-weight-bold text-grey-darken-3">Sohbetler</h2>
                    <v-chip size="x-small" color="primary" variant="flat">{{ contacts.length }}</v-chip>
                </div>
                <div class="search-wrapper">
                    <v-icon icon="mdi-magnify" class="search-icon" color="grey"></v-icon>
                    <input v-model="searchQuery" type="text" placeholder="Kişi ara..." class="custom-search-input">
                </div>
            </div>

            <div class="contact-list flex-grow-1 overflow-y-auto px-2 py-2">
                <div v-for="contact in filteredContacts" :key="contact.id" 
                     @click="selectContact(contact)"
                     class="contact-item px-3 py-3 rounded-lg mb-1 d-flex align-center cursor-pointer transition-swing"
                     :class="{'active-contact elevation-1': activeContact?.id === contact.id}">
                    <div class="relative mr-3">
                        <v-avatar size="44" :color="activeContact?.id === contact.id ? 'white' : 'grey-lighten-3'">
                            <span class="text-h6 font-weight-bold">{{ contact.name.charAt(0).toUpperCase() }}</span>
                        </v-avatar>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <h4 class="text-subtitle-2 font-weight-bold text-truncate">{{ contact.name }}</h4>
                        <p class="text-caption text-truncate mb-0 opacity-70">
                           {{ contact.role === 'instructor' ? 'Eğitmen' : 'Öğrenci' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="chat-main flex-grow-1 d-flex flex-column relative bg-chat-pattern">
            
            <div v-if="!activeContact" class="empty-state d-flex flex-column align-center justify-center h-100 px-4">
                <v-icon icon="mdi-message-text-outline" size="64" color="grey-lighten-1" class="mb-4"></v-icon>
                <h3 class="text-h5 text-grey-darken-2">Sohbet Başlatın</h3>
                <v-btn class="d-md-none mt-4" color="primary" @click="isSidebarOpen = true">Kişileri Gör</v-btn>
            </div>

            <template v-else>
                <div class="chat-header bg-white px-6 py-3 border-b d-flex align-center shadow-sm z-index-10">
                    <v-btn icon="mdi-arrow-left" variant="text" class="d-md-none mr-2" @click="activeContact = null; isSidebarOpen = true"></v-btn>
                    
                    <v-avatar color="primary" size="40" class="mr-3">
                        <span class="text-white font-weight-bold">{{ activeContact.name.charAt(0) }}</span>
                    </v-avatar>
                    
                    <div>
                        <div class="font-weight-bold">{{ activeContact.name }}</div>
                        <div class="text-caption text-success">Çevrimiçi</div>
                    </div>

                    <v-spacer></v-spacer>
                    
                    <div class="action-buttons">
                        <v-tooltip text="Sohbeti Temizle" location="bottom">
                            <template v-slot:activator="{ props }">
                                <v-btn 
                                    v-bind="props" 
                                    icon="mdi-delete-outline" 
                                    variant="text" 
                                    color="error" 
                                    size="small"
                                    @click="clearChat"
                                ></v-btn>
                            </template>
                        </v-tooltip>
                        <v-btn icon="mdi-dots-horizontal" variant="text" color="grey-darken-1" size="small"></v-btn>
                    </div>
                </div>

                <div ref="messagesContainer" class="messages-area flex-grow-1 overflow-y-auto px-4 py-6">
                    <div v-for="msg in messages" :key="msg.id" class="message-row d-flex mb-4 w-100"
                        :class="msg.sender_id === currentUser.id ? 'justify-end' : 'justify-start'">
                        
                        <div class="message-bubble elevation-1 px-4 py-2"
                            :class="msg.sender_id === currentUser.id ? 'me' : 'you'">
                            
                            <div v-if="msg.file_type === 'image' || (msg.file_url && msg.file_url.match(/\.(jpeg|jpg|gif|png)$/))" class="mb-2 mt-1">
                                <v-img 
                                    :src="msg.file_url" 
                                    width="200" 
                                    cover 
                                    class="rounded-lg bg-grey-lighten-2 cursor-pointer"
                                    @click="window.open(msg.file_url, '_blank')"
                                ></v-img>
                            </div>

                            <div v-else-if="msg.file_type === 'file' || msg.file_url" class="file-attachment mb-2 mt-1 p-2 rounded bg-grey-lighten-4 border d-flex align-center">
                                <v-icon icon="mdi-file-document-outline" color="primary" class="mr-2"></v-icon>
                                <div class="overflow-hidden mr-2">
                                    <div class="text-caption font-weight-bold text-truncate" style="max-width: 150px; color: #333;">
                                        {{ msg.file_name || 'Dosya Eki' }}
                                    </div>
                                </div>
                                <a :href="msg.file_url" download target="_blank" class="text-decoration-none">
                                    <v-icon icon="mdi-download" size="small" color="grey-darken-2"></v-icon>
                                </a>
                            </div>

                            <p v-if="msg.message" class="mb-0 text-body-2" style="white-space: pre-wrap;">{{ msg.message }}</p>
                            
                            <span class="timestamp">{{ new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</span>
                        </div>
                    </div>
                </div>

                <div class="chat-footer bg-white px-4 py-4 border-t relative">
                    
                    <div v-if="attachment" class="attachment-preview mb-2 d-flex align-center px-2">
                        <v-chip closable @click:close="removeAttachment" color="primary" label class="mr-2">
                            <v-icon start icon="mdi-paperclip"></v-icon>
                            {{ attachment.name }}
                        </v-chip>
                        <img v-if="attachmentPreview" :src="attachmentPreview" class="rounded elevation-1" style="height: 40px; width: 40px; object-fit: cover;">
                    </div>

                    <div v-if="showEmojiPicker" class="emoji-picker-container elevation-4">
                        <EmojiPicker :native="true" @select="onSelectEmoji" />
                    </div>

                    <form @submit.prevent="sendMessage" class="input-container d-flex align-center bg-grey-lighten-4 rounded-xl px-2 py-1 border transition-swing focus-within-border">
                        
                        <v-btn icon @click="toggleEmoji" variant="text" color="grey-darken-1" size="small" class="mx-1">
                            <v-icon :icon="showEmojiPicker ? 'mdi-keyboard' : 'mdi-emoticon-happy-outline'"></v-icon>
                        </v-btn>

                        <input type="file" ref="fileInput" class="d-none" @change="handleFileChange" accept="image/*, .pdf, .doc, .docx, .zip">
                        <v-btn icon="mdi-paperclip" variant="text" color="grey-darken-1" size="small" class="mr-2" @click="triggerFileSelect"></v-btn>
                        
                        <input 
                            v-model="newMessage" 
                            type="text" 
                            placeholder="Bir mesaj yazın..." 
                            class="flex-grow-1 bg-transparent border-none outline-none text-body-2 text-grey-darken-3 py-3"
                            autocomplete="off"
                        >
                        
                        <v-btn 
                            type="submit" 
                            icon="mdi-send" 
                            :color="(newMessage.trim() || attachment) ? 'primary' : 'grey-lighten-1'" 
                            variant="flat" 
                            size="small" 
                            class="ml-2 rounded-circle"
                            :disabled="!newMessage.trim() && !attachment"
                        ></v-btn>
                    </form>
                </div>
            </template>
        </div>
    </div>
</template>

<style scoped>
/* --- MEVCUT STİLLER KORUNDU, YENİLERİ EKLENDİ --- */
.chat-layout { height: calc(100vh - 64px); margin-top: 64px; display: flex; overflow: hidden; font-family: 'Inter', sans-serif; }
.z-index-10 { z-index: 10; }

/* Sidebar */
.chat-sidebar { width: 320px; min-width: 320px; height: 100%; border-right: 1px solid #e2e8f0; }
.search-wrapper { position: relative; margin-top: 8px; }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 18px; pointer-events: none; }
.custom-search-input { width: 100%; background-color: #f1f5f9; border: 1px solid transparent; border-radius: 8px; padding: 10px 12px 10px 36px; font-size: 14px; outline: none; transition: 0.2s; }
.custom-search-input:focus { background-color: #fff; border-color: #9155FD; }
.contact-item:hover { background-color: #f8fafc; }
.active-contact { background-color: #9155FD !important; color: white !important; }

/* Chat Main */
.bg-chat-pattern { background-color: #f8fafc; background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 24px 24px; }
.messages-area { scroll-behavior: smooth; }
.message-bubble { max-width: 75%; border-radius: 18px; font-size: 0.95rem; position: relative; }
.me { background: linear-gradient(135deg, #9155FD 0%, #804EE9 100%); color: white; border-bottom-right-radius: 4px; }
.you { background-color: white; color: #1e293b; border: 1px solid #e2e8f0; border-bottom-left-radius: 4px; }
.timestamp { display: block; font-size: 0.65rem; margin-top: 4px; text-align: right; opacity: 0.7; }

/* Footer & Emoji */
.input-container:focus-within { border-color: #9155FD; background-color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

/* Yeni Emoji Picker Stili */
.emoji-picker-container {
    position: absolute;
    bottom: 80px;
    left: 20px;
    z-index: 100;
}

/* Scrollbar */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

@media (max-width: 768px) {
    .chat-sidebar { width: 100%; border-right: none; }
    .chat-main { display: none; }
    .chat-layout.d-flex .chat-sidebar { display: none; }
}
</style>