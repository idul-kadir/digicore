const express = require('express');
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const axios = require('axios');

const penerima = 'server1';
const webhookUrl = 'https://clientarea.digicore.web.id/webhook';

const app = express();
app.use(express.json());

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    }
});

let reconnectAttempts = 0;
const maxReconnectAttempts = 10;

const initializeClient = () => {
    client.initialize();
};

app.get('/api/qr', (req, res) => {
    client.on('qr', (qr) => {
        qrcode.generate(qr, { small: true });
        res.json({ qr });
    });
});

client.on('ready', () => {
    console.log('Client is ready!');
    sendConnectionStatus('connected', 'status'); // Mengirim status saat terhubung
    reconnectAttempts = 0;
});

client.on('message', async (message) => {
    if (!message.fromMe && !message.isGroupMsg) {
        const senderNumber = message.from.split('@')[0];
        const timestamp = Math.floor(Date.now() / 1000);
        console.log(`Received message from ${senderNumber} at ${timestamp}: ${message.body}`);

        try {
            await axios.post(webhookUrl, {
                category: 'incoming_message', // Menambahkan kategori untuk pesan masuk
                route: penerima,
                sender: senderNumber,
                text: message.body,
                timestamp,
            });
        } catch (error) {
            console.error('Error sending to webhook:', error);
        }

        // Uncomment this line to send a response message
        // await client.sendMessage(`${senderNumber}@c.us`, 'Pesan anda akan segera kami proses');
    }
});

const sendConnectionStatus = async (status, category) => {
    try {
        await axios.post(webhookUrl, {
            category, // Menambahkan kategori untuk status koneksi
            status,
            timestamp: Math.floor(Date.now() / 1000),
            route: penerima
        });
    } catch (error) {
        console.error('Error sending connection status to webhook:', error);
    }
};

client.on('disconnected', () => {
    console.log('Client disconnected!');
    sendConnectionStatus('disconnected', 'status');
    autoReconnect();
});

const autoReconnect = () => {
    reconnectAttempts++;

    if (reconnectAttempts <= maxReconnectAttempts) {
        console.log(`Attempting to reconnect... (${reconnectAttempts}/${maxReconnectAttempts})`);

        setTimeout(() => {
            initializeClient();

            client.on('ready', () => {
                console.log('Reconnected successfully!');
                sendConnectionStatus('connected', 'status');
                reconnectAttempts = 0;
            });

            client.on('disconnected', () => {
                autoReconnect();
            });

            if (reconnectAttempts === maxReconnectAttempts) {
                sendConnectionStatus('failed_to_reconnect', 'status');
            }
        }, 15000);
    } else {
        console.log('Failed to reconnect after 10 attempts.');
    }
};

client.on('qr', (qr) => {
    qrcode.generate(qr, { small: true });
    sendConnectionStatus('waiting_for_qr', 'status'); // Mengirim status saat menunggu QR
});

// Fungsi untuk memeriksa status koneksi dan mengirimkan secara periodik
const checkAndSendConnectionStatus = async () => {
    const isReady = client.info && client.info.wid ? true : false; // Mengecek apakah client.info tersedia
    const status = isReady ? 'connected' : 'disconnected';

    try {
        await sendConnectionStatus(status, 'periodic_status'); // Mengirim status secara periodik
        console.log(`Periodic status sent: ${status}`);
    } catch (error) {
        console.error('Error sending periodic connection status:', error);
    }
};

// Menjadwalkan pengiriman status koneksi setiap 5 menit
setInterval(() => {
    checkAndSendConnectionStatus();
}, 5 * 60 * 1000); // 5 menit dalam milidetik

initializeClient();

app.post('/api/send-message', async (req, res) => {
    const { to, message } = req.body;

    if (!to || !message) {
        return res.status(400).json({ error: 'Nomor dan pesan harus diisi' });
    }

    try {
        await client.sendMessage(`${to}@c.us`, message);
        res.json({ success: true, message: 'Pesan berhasil dikirim' });
    } catch (error) {
        console.error('Error sending message:', error);
        res.status(500).json({ error: 'Gagal mengirim pesan' });
    }
});

const PORT = 3500;
app.listen(PORT, () => {
    console.log(`Server berjalan di http://localhost:${PORT}`);
});

