<?php
require 'function.php';
$header = getallheaders();
$data = file_get_contents("php://input");

$file = fopen("save.json","w+");
fwrite($file, $data);
fclose($file);

$file = fopen("header.json","w+");
fwrite($file, json_encode($header));
fclose($file);

$data = json_decode($data, true);

$nama_pengirim = $data['pushname'];
$id_pesan = $data['message']['id'];
$tujuan = format_nomor($data['from']);
if(strlen($data['from']) > 30){
  $pisah = explode(':',$data['from']);
  $tujuan = format_nomor($pisah[0]);
}

// $pesan_balasan = "Hallo _*$nama_pengirim*_ semua pesan dari nomor ini tidak perlu dibalas yah\n\n__________________________________\n\n🌐 DIGICORE - Solusi Digital Terbaik untuk Bisnis Anda! 🌐\n\nHai, DigiCores! 🙋‍♂️\n\nApakah Anda sedang mencari solusi digital lengkap untuk mendukung perkembangan bisnis Anda? Digicore.web.id siap membantu dengan layanan yang lengkap dan terpercaya! 🚀\n\n🔹 WhatsApp Gateway\nMudahkan komunikasi bisnis Anda dengan pelanggan melalui API WhatsApp yang stabil dan mudah diintegrasikan.\n\n🔹 VPN Tunnel\nPastikan keamanan koneksi Anda dengan VPN Tunnel dari kami, cocok untuk berbagai kebutuhan, mulai dari remote akses hingga keamanan jaringan.\n\n🔹 Domain & Hosting\nMulai bisnis online Anda dengan domain yang keren dan hosting yang cepat serta aman. Support terbaik untuk situs Anda aktif 24/7!\n\n🔹 VPS (Virtual Private Server)\nDapatkan VPS dengan berbagai pilihan server datacenter lokal dan internasional yang andal serta uptime tinggi. Cocok untuk website, aplikasi, dan kebutuhan server lainnya.\n\n🔹 IP Publik\nButuh IP Publik untuk bisnis atau proyek Anda? Kami menyediakan IP Publik dengan berbagai paket menarik!\n\n🔹 IT Consultant\nBingung dengan kebutuhan IT bisnis Anda? Konsultasikan dengan kami, tim ahli siap memberikan solusi terbaik!\n\nJangan lewatkan kesempatan untuk meningkatkan performa bisnis Anda dengan layanan kami. Hubungi kami sekarang untuk penawaran spesial bulan ini! 💸✨\n\n💬 Kontak Kami Sekarang\nKlik untuk chat: 089669106718\n\n🌐 Kunjungi website kami: https://digicore.web.id\n\nSalam hangat,\nTim Digicore";

$pesan_balasan = "Hallo _*$nama_pengirim*_ semua pesan dari nomor ini tidak perlu dibalas yah\n\nSalam\n🌐www.digicore.web.id";

sleep(20);
if(isset($id_pesan)){
  kirim_pesan($pesan_balasan,$tujuan,$id_pesan);
}