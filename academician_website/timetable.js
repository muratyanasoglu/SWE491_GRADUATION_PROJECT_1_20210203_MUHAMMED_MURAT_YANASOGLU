// Zaman çizelgesini oluşturma fonksiyonu
function generateTimetable() {
    const times = ["09:00 - 09:50", "10:00 - 10:50", "11:00 - 11:50", "12:00 - 12:50", "13:00 - 13:50", "14:00 - 14:50", "15:00 - 15:50", "16:00 - 16:50", "17:00 - 17:50", "18:00 - 18:50", "19:00 - 19:50"]; // Saat dilimleri
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']; // Günler
    const table = document.getElementById('timetable'); // Tablo elemanı seçiliyor

    times.forEach(time => { // Her saat dilimi için bir satır oluşturuluyor
        const row = table.insertRow(); // Yeni satır ekleniyor
        const timeCell = row.insertCell(); // Zaman hücresi ekleniyor
        timeCell.textContent = time; // Zaman hücresine zaman yazılıyor

        for (let day = 1; day <= 6; day++) {
            const cell = row.insertCell(); // Gün hücresi ekleniyor
            const data = timetableData[time]?.[day] || { activityType: 'Not Available', lectureCode: '' }; // Hücre verisi
            cell.textContent = data.activityType + (data.lectureCode ? `: ${data.lectureCode}` : ''); // Hücre içeriği
            cell.dataset.value = JSON.stringify({ time, day, ...data }); // Hücre verisi
            cell.onclick = function () { openModal(this); }; // Hücre tıklama işlemi
        }
    });
}

// Zaman çizelgesini sıfırlama fonksiyonu
function resetTable() {
    const cells = document.querySelectorAll('#timetable td[data-value]'); // Tüm hücreler seçiliyor
    cells.forEach(cell => cell.textContent = 'Not Available'); // Her hücre 'Not Available' olarak ayarlanıyor
}

// Bilgileri kaydetme ve yönlendirme fonksiyonu
async function saveInformation() {
    const year = document.getElementById('academicYear').value; // Akademik yıl alınıyor
    const semester = document.getElementById('academicSemester').value; // Akademik dönem alınıyor
    const cells = document.querySelectorAll('#timetable td[data-value]'); // Tüm hücreler seçiliyor

    const timetable = {}; // Zaman çizelgesi verisi

    cells.forEach(cell => {
        const data = JSON.parse(cell.dataset.value); // Hücre verisi alınıyor
        const { time, day, lectureCode, activityType } = data; // Veriden gerekli bilgiler alınıyor

        if (!timetable[time]) {
            timetable[time] = {};
        }

        timetable[time][day] = { lectureCode, activityType };
    });

    const academicId = document.getElementById('academicId').value; // Akademik kimlik alınıyor

    await fetch('save_timetable.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ year, semester, timetable, academicId }),
    });

    // window.location.href = 'https://neudc.vercel.app/'; // Gerçek kaydetme işlemi için buraya AJAX/fetch isteği eklenebilir.
}

// Modal penceresi açma fonksiyonu
var selectedCell;
function openModal(cell) {
    selectedCell = cell; // Seçilen hücre belirleniyor
    document.getElementById('myModal').style.display = 'block'; // Modal penceresi gösteriliyor
    document.getElementById('lectureCode').style.display = 'none'; // Ders kodu girişi gizleniyor
}

// Etkinlik ekleme fonksiyonu
function addActivity() {
    let data = JSON.parse(selectedCell.dataset.value); // Seçilen hücre verisi alınıyor
    const activityType = document.getElementById('activityType').value; // Etkinlik türü alınıyor
    if (activityType === 'In Lecture') { // Eğer etkinlik türü 'In Lecture' ise
        const lectureCode = document.getElementById('lectureCode').value; // Ders kodu alınıyor
        if (lectureCode !== '') {
            selectedCell.textContent = `In Lecture: ${lectureCode}`; // Seçilen hücreye ders kodu ekleniyor
            data = { ...data, lectureCode, activityType }; // Veriye ders kodu ve etkinlik türü ekleniyor
            document.getElementById('lectureCode').value = ''; // Ders kodu temizleniyor
        } else {
            alert('Please enter a lecture code.'); // Ders kodu girilmediyse uyarı veriliyor
            document.getElementById('lectureCode').style.display = 'block'; // Ders kodu girişi gösteriliyor
            return;
        }
    } else {
        selectedCell.textContent = activityType; // Diğer etkinlik türleri için seçilen hücre güncelleniyor
        data = { ...data, activityType }; // Veriye etkinlik türü ekleniyor
    }
    selectedCell.dataset.value = JSON.stringify(data); // Hücre verisi güncelleniyor
    closeModal(); // Modal penceresi kapatılıyor
}

// Modal penceresini kapatma fonksiyonu
function closeModal() {
    document.getElementById('myModal').style.display = 'none'; // Modal penceresi gizleniyor
}

// Sayfa yüklendiğinde zaman çizelgesi oluşturulması
window.onload = generateTimetable;

// Modal penceresi kapatma butonu işlevi
document.getElementsByClassName('close')[0].onclick = function () {
    closeModal();
};

// Aktivite tipi seçimi değiştiğinde çalışacak fonksiyon
document.getElementById('activityType').onchange = function () {
    const activityType = this.value; // Seçilen etkinlik türü alınıyor
    const lectureCodeInput = document.getElementById('lectureCode'); // Ders kodu girişi alınıyor
    if (activityType === 'In Lecture') { // Eğer etkinlik türü 'In Lecture' ise
        lectureCodeInput.style.display = 'block'; // Ders kodu girişi gösteriliyor
    } else {
        lectureCodeInput.style.display = 'none'; // Diğer durumlarda ders kodu girişi gizleniyor
    }
};
