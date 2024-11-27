<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. BUS MAKMUR - Pemesanan Tiket</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('bus.JPEG');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            text-align: left; /* Mengubah teks menjadi rata kiri */
        }

        h2 {
            text-align: center; /* Hanya judul yang tetap di tengah */
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input, select, button {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .hidden {
            display: none;
        }

        #ticket-details {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
        }
    </style>
</head>
<body>
    <div id="login-container" class="container">
        <h2>Login</h2>
        <form id="loginForm">
            <label for="username">Username:</label>
            <input type="text" id="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" required>

            <button type="submit">Login</button>
        </form>
        <p id="error-msg" class="hidden">Login gagal. Coba lagi!</p>
    </div>

    <div id="step1-container" class="container hidden">
        <h2>PT. BUS MAKMUR</h2>
        <form id="step1Form">
            <label for="nama">Nama Penumpang:</label>
            <input type="text" id="nama" name="nama" required>

            <!-- Pindahkan Kota Asal ke bawah Nama Penumpang -->
            <label for="kota-asal">Kota Asal:</label>
            <select id="kota-asal" name="kota-asal" required>
                <option value="">Pilih Kota Asal</option>
                <option value="Jakarta">Jakarta</option>
                <option value="Bandung">Bandung</option>
                <option value="Surabaya">Surabaya</option>
                <option value="Yogyakarta">Yogyakarta</option>
            </select>

            <label for="tujuan">Tujuan:</label>
            <select id="tujuan" name="tujuan" required>
                <option value="">Pilih Tujuan</option>
                <option value="Jakarta">Jakarta</option>
                <option value="Bandung">Bandung</option>
                <option value="Surabaya">Surabaya</option>
                <option value="Yogyakarta">Yogyakarta</option>
            </select>

            <label for="tanggal-keberangkatan">Tanggal Keberangkatan:</label>
            <input type="date" id="tanggal-keberangkatan" name="tanggal-keberangkatan" required>

            <label for="jumlah-tiket">Jumlah Tiket:</label>
            <input type="number" id="jumlah-tiket" name="jumlah-tiket" min="1" required>

            <button type="button" id="next-button">Next</button>
        </form>
    </div>

    <div id="step2-container" class="container hidden">
        <h2>PT. BUS MAKMUR</h2>
        <form id="step2Form">
            <label for="metode-pembayaran">Metode Pembayaran:</label>
            <select id="metode-pembayaran" name="metode-pembayaran" required>
                <option value="">Pilih Metode</option>
                <option value="Kartu Kredit">Kartu Kredit</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="E-Wallet">E-Wallet</option>
            </select>

            <div id="bank-selection" class="hidden">
                <label for="bank">Pilih Bank:</label>
                <select id="bank" name="bank">
                    <option value="">Pilih Bank</option>
                    <option value="Bank BCA">Bank BCA</option>
                    <option value="Bank Mandiri">Bank Mandiri</option>
                    <option value="Bank BRI">Bank BRI</option>
                    <option value="Bank BNI">Bank BNI</option>
                </select>
            </div>

            <div id="ewallet-selection" class="hidden">
                <label for="ewallet">Pilih E-Wallet:</label>
                <select id="ewallet" name="ewallet">
                    <option value="">Pilih E-Wallet</option>
                    <option value="OVO">OVO</option>
                    <option value="GoPay">GoPay</option>
                    <option value="DANA">DANA</option>
                    <option value="LinkAja">LinkAja</option>
                </select>
            </div>

            <button type="button" id="pay-button">Bayar</button>
        </form>
    </div>

    <div id="confirmation-container" class="container hidden">
        <h2>Pembayaran Berhasil!</h2>
        <p>Terima kasih, pembayaran Anda telah berhasil.</p>
        <button type="button" id="next-to-summary">Next</button>
    </div>

    <div id="summary-container" class="container hidden">
        <h2>TIKET ANDA</h2>
        <p id="summary-nama"></p>
        <p id="summary-tujuan"></p>
        <p id="summary-tanggal"></p>
        <p id="summary-jumlah"></p>
        <p id="summary-harga"></p>
        <p id="summary-waktu"></p>
        <button id="download-button">Unduh Tiket (PDF)</button>
        <button id="logout-button-summary">Logout</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginContainer = document.getElementById('login-container');
            const step1Container = document.getElementById('step1-container');
            const step2Container = document.getElementById('step2-container');
            const confirmationContainer = document.getElementById('confirmation-container');
            const summaryContainer = document.getElementById('summary-container');
            const errorMsg = document.getElementById('error-msg');
            const nextButton = document.getElementById('next-button');
            const payButton = document.getElementById('pay-button');
            const nextToSummaryButton = document.getElementById('next-to-summary');
            const downloadButton = document.getElementById('download-button');
            const logoutButtonSummary = document.getElementById('logout-button-summary');
            const step1Form = document.getElementById('step1Form');
            const step2Form = document.getElementById('step2Form');
            const metodePembayaran = document.getElementById('metode-pembayaran');
            const bankSelection = document.getElementById('bank-selection');
            const ewalletSelection = document.getElementById('ewallet-selection');

            const hargaTujuan = {
                "Jakarta": 100000,
                "Bandung": 80000,
                "Surabaya": 150000,
                "Yogyakarta": 120000
            };

            const waktuTujuan = {
                "Jakarta": 5,
                "Bandung": 3,
                "Surabaya": 8,
                "Yogyakarta": 6
            };

            loginForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                if (username === 'user' && password === 'pass123') {
                    loginContainer.classList.add('hidden');
                    step1Container.classList.remove('hidden');
                } else {
                    errorMsg.classList.remove('hidden');
                }
            });

            nextButton.addEventListener('click', function() {
                if (step1Form.reportValidity()) {
                    step1Container.classList.add('hidden');
                    step2Container.classList.remove('hidden');
                }
            });

            metodePembayaran.addEventListener('change', function() {
                if (metodePembayaran.value === 'Transfer Bank') {
                    bankSelection.classList.remove('hidden');
                    ewalletSelection.classList.add('hidden');
                } else if (metodePembayaran.value === 'E-Wallet') {
                    ewalletSelection.classList.remove('hidden');
                    bankSelection.classList.add('hidden');
                } else {
                    bankSelection.classList.add('hidden');
                    ewalletSelection.classList.add('hidden');
                }
            });

            payButton.addEventListener('click', function() {
                if (step2Form.reportValidity()) {
                    step2Container.classList.add('hidden');
                    confirmationContainer.classList.remove('hidden');
                }
            });

            nextToSummaryButton.addEventListener('click', function() {
                confirmationContainer.classList.add('hidden');
                summaryContainer.classList.remove('hidden');

                const nama = document.getElementById('nama').value;
                const kotaAsal = document.getElementById('kota-asal').value;
                const tujuan = document.getElementById('tujuan').value;
                const tanggalKeberangkatan = document.getElementById('tanggal-keberangkatan').value;
                const jumlahTiket = document.getElementById('jumlah-tiket').value;
                const hargaPerTiket = hargaTujuan[tujuan];
                const totalHarga = hargaPerTiket * jumlahTiket;
                const waktuPerjalanan = waktuTujuan[tujuan];

                document.getElementById('summary-nama').innerText = `Nama: ${nama}`;
                document.getElementById('summary-tujuan').innerText = `Tujuan: ${tujuan}`;
                document.getElementById('summary-tanggal').innerText = `Tanggal: ${tanggalKeberangkatan}`;
                document.getElementById('summary-jumlah').innerText = `Jumlah Tiket: ${jumlahTiket}`;
                document.getElementById('summary-harga').innerText = `Harga Total: Rp ${totalHarga.toLocaleString()}`;
                document.getElementById('summary-waktu').innerText = `Estimasi Waktu: ${waktuPerjalanan} jam`;
            });

            downloadButton.addEventListener('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Generate QR Code
                const qr = new QRious({
                    value: "Tiket: " + document.getElementById('summary-nama').innerText + " " + document.getElementById('summary-tujuan').innerText,
                    size: 100
                });

                doc.text("TIKET ANDA - PT. BUS MAKMUR", 20, 20);
                doc.text(document.getElementById('summary-nama').innerText, 20, 30);
                doc.text(document.getElementById('summary-tujuan').innerText, 20, 40);
                doc.text(document.getElementById('summary-tanggal').innerText, 20, 50);
                doc.text(document.getElementById('summary-jumlah').innerText, 20, 60);
                doc.text(document.getElementById('summary-harga').innerText, 20, 70);
                doc.text(document.getElementById('summary-waktu').innerText, 20, 80);

                // Add QR code to PDF
                doc.addImage(qr.toDataURL(), 'PNG', 20, 90, 50, 50);

                doc.save("tiket_bus.pdf");
            });

            logoutButtonSummary.addEventListener('click', function() {
                summaryContainer.classList.add('hidden');
                loginContainer.classList.remove('hidden');
                loginForm.reset();
                step1Form.reset();
                step2Form.reset();
                errorMsg.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
