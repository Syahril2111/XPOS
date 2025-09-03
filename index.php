<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>XPOS - Aplikasi Kasir Modern</title>
  <meta name="description" content="XPOS adalah aplikasi kasir modern untuk UMKM Indonesia. Mudah digunakan, fitur lengkap, dan mendukung kebutuhan operasional toko Anda.">
  <link rel="icon" type="image/png" href="assets/images/logo.png">
  <!-- Hubungkan ke file CSS -->
  <link rel="stylesheet" href="assets/style.css">

</head>
<script>
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll(".like-btn").forEach(btn => {
    btn.addEventListener("click", function() {
      let id = this.dataset.id;
      let span = this.querySelector("span");
      fetch("like.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "id=" + id
      })
      .then(res => res.text())
      .then(data => {
        span.textContent = data; // update jumlah like
      });
    });
  });
});
</script>

<body>

  <!-- HEADER -->
  <header>
    <div>
      <img src="assets/images/logo.ico" alt="Logo Aplikasi XPOS">
    </div>
    <nav>
      <a href="#home">Beranda</a>
      <a href="#fitur">Fitur</a>
      <a href="#harga">Harga</a>
      <a href="#galeri">Galeri</a>
      <a href="#kontak">Kontak</a>
    </nav>
  </header>

  <!-- HERO -->
  <section class="hero" id="home">
    <div class="hero-text">
      <h1><span style="color:#ff0000;">X</span><span style="color:#cfc538;">POS</span> - Aplikasi Kasir Modern Untuk UMKM Indonesia</h1>

      <p>
        XPOS adalah aplikasi kasir yang dirancang khusus untuk minimarket, toko sembako, apotek, dan usaha retail lainnya. 
        Mudah digunakan, fitur lengkap, dan mendukung kebutuhan operasional toko Anda.
      </p>

      <a href="assets/Setup/X-POS.exe" class="btn btn-download" onclick="incrementDownload()">⬇ Download Sekarang Juga Gratis</a>

      <a href="https://wa.me/6282114975811?text=Halo%20saya%20tertarik%20dengan%20aplikasi%20XPOS" target="_blank" class="btn btn-wa">💬 Hubungi Kami wa 1</a>
       <a href="https://wa.me/6285591007640?text=Halo%20saya%20tertarik%20dengan%20aplikasi%20XPOS" target="_blank" class="btn btn-wa">💬 Hubungi Kami wa 2</a>
      
      <!-- Tombol Shopee -->
         <a href="https://s.shopee.co.id/3qDJ3bOdrU" class="btn btn-shopee" target="_blank" class="btn btn-shopee">Beli di Shopee </a>
   

      <div class="download-stats">Sudah dipakai oleh <span id="user-count">854</span>+ user</div>
    <p>
        Follow Akun Media Sosial Kami.
    </p>

        <!-- Tombol Sosial Media -->
  <div class="social-links">
    <a href="https://www.facebook.com/share/19Y9GwMYbf/" target="_blank" class="facebook"><i class="fab fa-facebook-f"></i></a>
    <a href="https://youtube.com/@xpos2?si=fUlM6PCrYtMaVJku" target="_blank" class="youtube"><i class="fab fa-youtube"></i></a>
    <a href="https://www.tiktok.com/@balak_600?_t=ZS-8zIAQ3xJtrU&_r=1" target="_blank" class="tiktok"><i class="fab fa-tiktok"></i></a>
  </div>
    </div>

    
   
    <div class="img-group">
      <img src="assets/images/XPOS1.png" alt="Tampilan aplikasi kasir XPOS 1">
      <img src="assets/images/XPOS2.png" alt="Tampilan aplikasi kasir XPOS 2">
      <img src="assets/images/XPOS3.png" alt="Tampilan aplikasi kasir XPOS 3">
    </div>
  </section>

  <!-- === FITUR === -->
  <section class="features" id="fitur">
    <h2>Fitur Unggulan</h2>
    <div class="feature-list">
      <div class="feature-item">
        <img src="assets/images/icon-01.png" alt="Icon Manajemen Stok">
        <h3>Manajemen Stok</h3>
        <p>Pantau stok barang secara real-time dengan notifikasi stok menipis.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-02.png" alt="Icon Laporan Keuangan">
        <h3>Laporan Keuangan</h3>
        <p>Otomatis menghasilkan laporan harian, mingguan, dan bulanan.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-03.png" alt="Icon Multi User">
        <h3>Multi User</h3>
        <p>Dapat digunakan banyak kasir sekaligus dengan hak akses berbeda.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-04.png" alt="Icon Integrasi Hardware">
        <h3>Integrasi Hardware</h3>
        <p>Mendukung printer struk, barcode scanner, dan laci kasir otomatis.</p>
      </div>

      <!-- Fitur tambahan -->
      <div class="feature-item">
        <img src="assets/images/icon-05.png" alt="Icon Manajemen Member">
        <h3>Manajemen Member</h3>
        <p>Mengelola data pelanggan dan memberikan keuntungan eksklusif bagi member.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-06.png" alt="Icon Harga Bertingkat">
        <h3>Harga Bertingkat</h3>
        <p>Atur harga berbeda berdasarkan jumlah pembelian atau kategori pelanggan.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-07.png" alt="Icon Promo Tukar Poin">
        <h3>Promo Tukar Poin</h3>
        <p>Member dapat menukarkan poin dengan produk atau diskon khusus.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-08.png" alt="Icon Promo Diskon">
        <h3>Promo Diskon</h3>
        <p>Mudah membuat promo potongan harga untuk menarik pelanggan.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-09.png" alt="Icon Promo Cashback">
        <h3>Promo Cashback</h3>
        <p>Voucher cashback otomatis untuk meningkatkan loyalitas pelanggan.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-10.png" alt="Icon Gamifikasi Belanja">
        <h3>Pencet-Pencet Berhadiah</h3>
        <p>Program gamifikasi belanja dengan hadiah menarik setiap transaksi.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-11.png" alt="Icon Promo Tebus Murah">
        <h3>Promo Tebus Murah</h3>
        <p>Pelanggan bisa menebus produk tertentu dengan harga lebih murah.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-12.png" alt="Icon Promo Gratis">
        <h3>Promo Serba Gratis</h3>
        <p>Tawarkan promo beli satu gratis satu atau gratis produk tertentu.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-13.png" alt="Icon Paket Bundling">
        <h3>Paket Bundling</h3>
        <p>Kombinasikan beberapa produk dalam satu paket dengan harga spesial.</p>
      </div>
      <div class="feature-item">
        <img src="assets/images/icon-14.png" alt="Icon Planogram">
        <h3>Planogram</h3>
        <p>Atur tata letak produk di rak untuk memaksimalkan penjualan.</p>
      </div>
    </div>
  </section>

  <!-- === HARGA === -->
  <section class="pricing" id="harga">
    <h2>Pilihan Paket Harga</h2>
    <div class="pricing-cards">
      <div class="pricing-card">
        <h3>XPOS Standar</h3>
        <div class="price">Rp 899.000</div>
        <p>Cocok untuk toko kecil dan minimarket.</p>
        <a href="https://wa.me/6282114975811?text=Halo%20saya%20ingin%20beli%20XPOS%20Standar" class="btn btn-wa">💬 Pesan Sekarang</a>
      </div>
      <div class="pricing-card">
        <h3>XPOS Unlimited</h3>
        <div class="price">Rp 1.899.000</div>
        <p>Fitur lengkap tanpa batasan user.</p>
        <a href="https://wa.me/6282114975811?text=Halo%20saya%20ingin%20beli%20XPOS%20Unlimited" class="btn btn-wa">💬 Pesan Sekarang</a>
      </div>
      <div class="pricing-card">
        <h3>Paket Lengkap Full Set</h3>
        <div class="price">Rp 4.990.000</div>
        <p>Sudah termasuk software + perangkat kasir lengkap.</p>
        <a href="https://wa.me/6282114975811?text=Halo%20saya%20ingin%20beli%20Paket%20Lengkap%20Full%20Set" class="btn btn-wa">💬 Pesan Sekarang</a>
      </div>
    </div>
  </section>

  <!-- === TABEL PERBANDINGAN === -->
  <section class="pricing" id="perbandingan">
    <h2>Perbandingan Fitur Tiap Paket</h2>
    <div style="overflow-x:auto;">
      <table class="comparison-table">
        <tr>
          <th>Fitur</th>
          <th>XPOS Standar</th>
          <th>XPOS Unlimited</th>
          <th>Full Set</th>
        </tr>
        <tr><td>Manajemen Stok</td><td>✔️</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Laporan Keuangan</td><td>✔️</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Multi User</td><td>❌</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Integrasi Hardware</td><td>✔️ (Printer)</td><td>✔️</td><td>✔️ (Include perangkat)</td></tr>
        <tr><td>Integrasi E-Transaksi(Pulsa,Token,Top Up ,Tagihan dll)</td><td>❌</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Laporan Laba Rugi</td><td>✔️</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Manajemen Member</td><td>✔️</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Promo & Diskon</td><td>✔️</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Planogram</td><td>✔️</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Absensi</td><td>✔️</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Integrasi QRIS</td><td>❌</td><td>✔️</td><td>✔️</td></tr>
        <tr><td>Konsinyasi</td><td>❌</td><td>✔️</td><td>✔️</td></tr>
         <tr><td>Hutang & Piutang</td><td>✔️</td><td>✔️</td><td>✔️</td></tr>
      </table>
    </div>
  </section>

  <!-- === GALERI === -->
  <section class="gallery" id="galeri">
    <h2>Gambar Aplikasi</h2>
    <div class="gallery-container" id="gallery-container"></div>
    <div id="gallery-pagination" class="gallery-pagination"></div>

  </section>

  <!-- LIGHTBOX -->
  <div id="lightbox" class="lightbox" onclick="this.style.display='none'">
    <img id="lightbox-img" src="" alt="Preview Galeri XPOS">
  </div>

<!-- === FORM & KOMENTAR MODERN === -->
<section id="komentar">
  <h2>Tinggalkan Komentar</h2>
  
  <form action="simpan_komentar.php" method="POST" class="comment-form">
    <input type="text" name="nama" placeholder="Nama Anda" required>
    <input type="email" name="email" placeholder="Email (opsional)">
    <textarea name="pesan" placeholder="Tulis komentar Anda..." required></textarea>
    <button type="submit" class="btn-comment">💬 Kirim Komentar</button>
  </form>

  <h3 class="list-title">Komentar Terbaru</h3>
  <div class="comment-list">
    <?php
    $conn = new mysqli("localhost", "root", "", "xpos_db");
    if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

    $limit = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // total komentar
    $total_result = $conn->query("SELECT COUNT(*) AS total FROM komentar_xpos");
    $total_row = $total_result->fetch_assoc();
    $total_comments = $total_row['total'];
    $total_pages = ceil($total_comments / $limit);

    // ambil komentar
    $result = $conn->query("SELECT * FROM komentar_xpos ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $initial = strtoupper(substr($row['nama'],0,1));
        echo "<div class='comment-box' data-id='".$row['id']."'>";
        echo "<div class='avatar'>$initial</div>";
        echo "<div class='comment-content'>";
        echo "<div class='comment-header'>";
        echo "<strong>".htmlspecialchars($row['nama'])."</strong>";
        echo "<small>".date("d M Y H:i", strtotime($row['tanggal']))."</small>";
        echo "</div>";
        echo "<p>".nl2br(htmlspecialchars($row['pesan']))."</p>";
        echo "<button class='like-btn' data-id='".$row['id']."'>👍 Suka (<span>".$row['likes']."</span>)</button>";
        echo "</div></div>";
      }

      // pagination
      echo "<div class='pagination'>";
      for ($i=1; $i<=$total_pages; $i++){
        $active = ($i==$page) ? "active" : "";
        echo "<a href='?page=$i#komentar' class='$active'>$i</a>";
      }
      echo "</div>";

    } else {
      echo "<p class='no-comment'>Belum ada komentar, jadilah yang pertama!</p>";
    }
    $conn->close();
    ?>
  </div>
</section>

<style>
/* ===== Container ===== */
#komentar {
  max-width: 700px; margin:50px auto; padding:30px;
  background:#fafafa; border-radius:12px;
  box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

/* ===== Judul ===== */
#komentar h2 { text-align:center; margin-bottom:20px; color:#1e88e5; }
#komentar .list-title { margin-top:40px; margin-bottom:15px; font-size:18px; color:#333; }

/* ===== Form ===== */
.comment-form input, .comment-form textarea {
  width:100%; padding:12px; margin:8px 0; border-radius:8px;
  border:1px solid #ccc; font-size:14px; transition:0.3s;
}
.comment-form input:focus, .comment-form textarea:focus { border-color:#1e88e5; outline:none; }
.comment-form textarea { min-height:100px; resize:vertical; }

.comment-form .btn-comment {
  width:100%; background:#ff7a00; color:#fff;
  padding:12px 0; border:none; border-radius:8px;
  font-weight:bold; font-size:15px; cursor:pointer;
  transition:all 0.3s;
}
.comment-form .btn-comment:hover { background:#e66b00; transform:translateY(-2px); }

/* ===== Comment List ===== */
.comment-list { margin-top:25px; }
.comment-box {
  display:flex; align-items:flex-start; padding:15px;
  margin-bottom:15px; background:#fff; border-radius:12px;
  box-shadow:0 2px 8px rgba(0,0,0,0.08); transition:0.3s;
}
.comment-box:hover { transform:translateY(-2px); }

.avatar {
  width:45px; height:45px; border-radius:50%;
  background:#1e88e5; color:#fff; font-weight:bold;
  display:flex; justify-content:center; align-items:center;
  font-size:16px; margin-right:12px;
}

.comment-content { flex:1; }
.comment-header { display:flex; justify-content:space-between; margin-bottom:6px; }
.comment-header strong { font-size:14px; color:#333; }
.comment-header small { font-size:12px; color:#777; }
.comment-content p { margin:0; line-height:1.5; font-size:14px; color:#555; }

.like-btn {
  margin-top:8px; padding:6px 12px; border:none; border-radius:6px;
  background:#eee; cursor:pointer; font-size:13px;
  transition:0.3s;
}
.like-btn:hover { background:#ddd; transform:translateY(-1px); }

/* ===== No Comment ===== */
.no-comment { text-align:center; color:#777; font-style:italic; margin-top:15px; }

/* ===== Pagination ===== */
.pagination { margin-top:20px; text-align:center; }
.pagination a {
  display:inline-block; padding:6px 12px; margin:0 4px;
  background:#f0f0f0; color:#333; border-radius:6px;
  text-decoration:none; transition:0.3s;
}
.pagination a.active, .pagination a:hover { background:#1e88e5; color:#fff; }
</style>



<!-- Tombol file pendukung -->
<div class="support-files">
  <h4>File Pendukung Remote:</h4>
  <a href="assets/Setup/UltraViewer_setup_6.6.124_id.exe" class="btn btn-download">⬇ Download Ultra Viewer</a>
  <a href="assets/Setup/AnyDesk.exe" class="btn btn-download">📦 Download Anydesk</a>
</div>

<script type="text/javascript">
  (function(d, t) {
      var v = d.createElement(t), s = d.getElementsByTagName(t)[0];
      v.onload = function() {
        window.voiceflow.chat.load({
          verify: { projectID: '68b6d4a3809a2c22e96ca225' },
          url: 'https://general-runtime.voiceflow.com',
          versionID: 'production',
          voice: {
            url: "https://runtime-api.voiceflow.com"
          }
        });
      }
      v.src = "https://cdn.voiceflow.com/widget-next/bundle.mjs"; v.type = "text/javascript"; s.parentNode.insertBefore(v, s);
  })(document, 'script');
</script>

<!-- FOOTER -->
<footer id="kontak">
  <p>Hubungi kami: <br> 
    Email: <a href="mailto:Kasirxpos@gmail.com">KasirXpos@gmail.com</a> | 
    WhatsApp: <a href="https://wa.me/6282114975811">+62 821-1497-5811</a>
  </p>

  <!-- Tombol Sosial Media -->
  <div class="social-links">
    <a href="https://www.facebook.com/share/19Y9GwMYbf/" target="_blank" class="facebook"><i class="fab fa-facebook-f"></i></a>
    <a href="https://youtube.com/@xpos2?si=fUlM6PCrYtMaVJku" target="_blank" class="youtube"><i class="fab fa-youtube"></i></a>
    <a href="https://www.tiktok.com/@balak_600?_t=ZS-8zIAQ3xJtrU&_r=1" target="_blank" class="tiktok"><i class="fab fa-tiktok"></i></a>
    
  </div>

  <p>© 2025 XPOS. Semua Hak Dilindungi.</p>
</footer>

<!-- Tambahkan FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Tombol Sosial Media */
.social-links {
  margin: 20px 0;
  text-align: center;
}
.social-links a {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  width: 45px; 
  height: 45px;
  margin: 0 8px;
  border-radius: 50%;
  color: white;
  font-size: 20px;
  transition: 0.3s;
}
.social-links a.facebook { background: #1877f2; }
.social-links a.youtube { background: #ff0000; }
.social-links a.tiktok { background: #000; }
.social-links a:hover { transform: scale(1.1); opacity: 0.8; }
</style>


 <!-- === GALERI XPOS === -->
<div id="gallery-container" class="gallery"></div>
<div id="gallery-pagination" class="gallery-pagination"></div>

<!-- Lightbox -->
<div id="lightbox" class="lightbox">
  <img id="lightbox-img" src="" alt="Galeri XPOS">
</div>

<style>
/* Galeri Grid */


/* Pagination */
.gallery-pagination {
  text-align: center;
  margin-top: 15px;
}
.gallery-pagination button {
  padding: 6px 12px;
  margin: 0 4px;
  border: none;
  border-radius: 6px;
  background: #f0f0f0;
  cursor: pointer;
  transition: all 0.3s;
}
.gallery-pagination button:hover { background: #1e88e5; color: #fff; }
.gallery-pagination button.active { background: #1e88e5; color: #fff; }

/* Lightbox */
.lightbox {
  display: none;
  position: fixed;
  top:0; left:0; width:100%; height:100%;
  background: rgba(0,0,0,0.8);
  justify-content: center;
  align-items: center;
  z-index: 999;
}
.lightbox img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.5);
}
</style>

<script>
const images = [
  "XPOS6.png","XPOS7.png","XPOS8.png","XPOS9.png","XPOS10.png",
  "XPOS11.png","XPOS12.png","XPOS13.png","XPOS14.png","XPOS15.png",
  "XPOS1.png","XPOS2.png","XPOS3.jpg","XPOS4.jpg","XPOS5.jpg"
];

const container = document.getElementById("gallery-container");
const lightbox = document.getElementById("lightbox");
const lightboxImg = document.getElementById("lightbox-img");
const perPage = 6;
let currentPage = 1;
const totalPages = Math.ceil(images.length / perPage);

function renderGallery(page) {
  container.innerHTML = "";
  let start = (page - 1) * perPage;
  let end = start + perPage;
  let pageImages = images.slice(start, end);

  pageImages.forEach(img => {
    let el = document.createElement("img");
    el.src = "assets/images/Galery/" + img;
    el.alt = "Galeri Aplikasi XPOS";
    el.onclick = () => { 
      lightbox.style.display = "flex"; 
      lightboxImg.src = el.src; 
    };
    container.appendChild(el);
  });

  renderPagination();
}

function renderPagination() {
  const pagination = document.getElementById("gallery-pagination");
  pagination.innerHTML = "";
  for (let i=1; i<=totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    btn.className = (i===currentPage) ? "active" : "";
    btn.onclick = () => { currentPage=i; renderGallery(currentPage); };
    pagination.appendChild(btn);
  }
}

// Tutup lightbox saat diklik di luar gambar
lightbox.addEventListener("click", (e) => {
  if (e.target !== lightboxImg) lightbox.style.display="none";
});

// render pertama
renderGallery(currentPage);

// Counter user animasi
let userCount = document.getElementById("user-count");
let target = 345, count = 0, speed = 20;
let interval = setInterval(() => {
  if (count < target) {
    count += Math.ceil(target/200);
    userCount.innerText = count.toLocaleString("id-ID");
  } else {
    userCount.innerText = target.toLocaleString("id-ID");
    clearInterval(interval);
  }
}, speed);


    // Counter download
    function incrementDownload() {
      alert("Terima kasih sudah mendownload XPOS!");
    }
  </script>
</body>
</html>
