from pathlib import Path
from docx import Document
from docx.enum.section import WD_SECTION
from docx.enum.table import WD_CELL_VERTICAL_ALIGNMENT, WD_TABLE_ALIGNMENT
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_BREAK
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
from docx.shared import Cm, Inches, Pt, RGBColor


OUT = Path(r"D:\Website Informatika\docs\pembelajaran-mesin")
OUT.mkdir(parents=True, exist_ok=True)

NAVY = "17365D"
BLUE = "DCE6F1"
PALE = "F3F6FA"
GRAY = "D9E1F2"
LIGHT = "EEF2F7"
WHITE = "FFFFFF"
BLACK = RGBColor(0, 0, 0)


def set_cell_shading(cell, fill):
    tc_pr = cell._tc.get_or_add_tcPr()
    shd = tc_pr.find(qn("w:shd"))
    if shd is None:
        shd = OxmlElement("w:shd")
        tc_pr.append(shd)
    shd.set(qn("w:fill"), fill)


def set_cell_margins(cell, top=100, start=120, bottom=100, end=120):
    tc = cell._tc
    tc_pr = tc.get_or_add_tcPr()
    tc_mar = tc_pr.first_child_found_in("w:tcMar")
    if tc_mar is None:
        tc_mar = OxmlElement("w:tcMar")
        tc_pr.append(tc_mar)
    for m, v in (("top", top), ("start", start), ("bottom", bottom), ("end", end)):
        node = tc_mar.find(qn(f"w:{m}"))
        if node is None:
            node = OxmlElement(f"w:{m}")
            tc_mar.append(node)
        node.set(qn("w:w"), str(v))
        node.set(qn("w:type"), "dxa")


def set_repeat_table_header(row):
    tr_pr = row._tr.get_or_add_trPr()
    tbl_header = OxmlElement("w:tblHeader")
    tbl_header.set(qn("w:val"), "true")
    tr_pr.append(tbl_header)


def set_keep_with_next(paragraph):
    paragraph.paragraph_format.keep_with_next = True


def configure_document(doc, title):
    sec = doc.sections[0]
    sec.page_width = Inches(8.5)
    sec.page_height = Inches(11)
    sec.top_margin = Cm(2.1)
    sec.bottom_margin = Cm(2.0)
    sec.left_margin = Cm(2.4)
    sec.right_margin = Cm(2.2)

    styles = doc.styles
    normal = styles["Normal"]
    normal.font.name = "Aptos"
    normal.font.size = Pt(10)
    normal.font.color.rgb = BLACK
    normal.paragraph_format.space_after = Pt(4)
    normal.paragraph_format.line_spacing = 1.05

    for style_name, size, bold, before, after in [
        ("Title", 24, True, 0, 14),
        ("Subtitle", 12, False, 0, 10),
        ("Heading 1", 17, True, 12, 7),
        ("Heading 2", 13, True, 10, 5),
        ("Heading 3", 11, True, 8, 4),
    ]:
        style = styles[style_name]
        style.font.name = "Aptos Display" if style_name != "Normal" else "Aptos"
        style.font.size = Pt(size)
        style.font.bold = bold
        style.font.color.rgb = BLACK
        style.paragraph_format.space_before = Pt(before)
        style.paragraph_format.space_after = Pt(after)
        style.paragraph_format.keep_with_next = True

    footer = sec.footer
    p = footer.paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r = p.add_run(f"{title}  |  Pembelajaran Mesin")
    r.font.name = "Aptos"
    r.font.size = Pt(8)
    r.font.color.rgb = RGBColor(90, 90, 90)


def add_page_number(paragraph):
    paragraph.add_run("  |  ")
    run = paragraph.add_run()
    fld_char = OxmlElement("w:fldChar")
    fld_char.set(qn("w:fldCharType"), "begin")
    instr = OxmlElement("w:instrText")
    instr.set(qn("xml:space"), "preserve")
    instr.text = " PAGE "
    sep = OxmlElement("w:fldChar")
    sep.set(qn("w:fldCharType"), "separate")
    txt = OxmlElement("w:t")
    txt.text = "1"
    end = OxmlElement("w:fldChar")
    end.set(qn("w:fldCharType"), "end")
    run._r.extend([fld_char, instr, sep, txt, end])


def add_cover(doc, title, subtitle):
    p = doc.add_paragraph(style="Title")
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p.paragraph_format.space_before = Pt(95)
    p.add_run(title)
    p2 = doc.add_paragraph(style="Subtitle")
    p2.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p2.add_run(subtitle)
    p3 = doc.add_paragraph()
    p3.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p3.paragraph_format.space_before = Pt(28)
    r = p3.add_run("Program Studi Informatika\nSemester 5  |  3 SKS\nTahun Akademik 2026/2027")
    r.font.size = Pt(12)
    r.bold = True
    p4 = doc.add_paragraph()
    p4.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p4.paragraph_format.space_before = Pt(85)
    p4.add_run("Disusun berdasarkan RPS G03231546 Pembelajaran Mesin").italic = True
    doc.add_page_break()


def add_para(doc, text, bold_lead=None):
    p = doc.add_paragraph()
    if bold_lead and text.startswith(bold_lead):
        p.add_run(bold_lead).bold = True
        p.add_run(text[len(bold_lead):])
    else:
        p.add_run(text)
    return p


def add_bullets(doc, items, level=0):
    for item in items:
        p = doc.add_paragraph(style="List Bullet" if level == 0 else "List Bullet 2")
        p.add_run(item)


def add_numbered(doc, items):
    for index, item in enumerate(items, start=1):
        p = doc.add_paragraph()
        p.paragraph_format.left_indent = Cm(0.55)
        p.paragraph_format.first_line_indent = Cm(-0.45)
        p.add_run(f"{index}. ").bold = True
        p.add_run(item)


def add_table(doc, headers, rows, widths=None, font_size=9):
    table = doc.add_table(rows=1, cols=len(headers))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = False
    table.style = "Table Grid"
    hdr = table.rows[0]
    set_repeat_table_header(hdr)
    for i, h in enumerate(headers):
        c = hdr.cells[i]
        set_cell_shading(c, NAVY)
        c.vertical_alignment = WD_CELL_VERTICAL_ALIGNMENT.CENTER
        set_cell_margins(c)
        p = c.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r = p.add_run(str(h))
        r.bold = True
        r.font.color.rgb = RGBColor(255, 255, 255)
        r.font.size = Pt(font_size)
        if widths:
            c.width = Cm(widths[i])
    for ri, row in enumerate(rows):
        cells = table.add_row().cells
        for i, value in enumerate(row):
            c = cells[i]
            c.vertical_alignment = WD_CELL_VERTICAL_ALIGNMENT.CENTER
            set_cell_margins(c)
            if ri % 2:
                set_cell_shading(c, PALE)
            p = c.paragraphs[0]
            p.paragraph_format.space_after = Pt(1)
            if i == 0 and len(str(value)) < 14:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            r = p.add_run(str(value))
            r.font.size = Pt(font_size)
            if widths:
                c.width = Cm(widths[i])
    doc.add_paragraph().paragraph_format.space_after = Pt(1)
    return table


def add_code(doc, code):
    table = doc.add_table(rows=1, cols=1)
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.style = "Table Grid"
    cell = table.cell(0, 0)
    set_cell_shading(cell, LIGHT)
    set_cell_margins(cell, 130, 150, 130, 150)
    p = cell.paragraphs[0]
    p.paragraph_format.space_after = Pt(0)
    for i, line in enumerate(code.strip().splitlines()):
        if i:
            p.add_run().add_break()
        r = p.add_run(line)
        r.font.name = "Consolas"
        r.font.size = Pt(8.5)
    doc.add_paragraph().paragraph_format.space_after = Pt(1)


modules = [
    {
        "n": 1,
        "title": "Orientasi dan Alur Kerja Pembelajaran Mesin",
        "sub": "Pengantar",
        "link": "Membentuk bahasa bersama sebelum mahasiswa bekerja dengan data dan algoritma.",
        "goals": ["Menjelaskan posisi machine learning dalam AI dan data science.", "Membedakan masalah prediksi, deskripsi, dan pengambilan keputusan.", "Mengidentifikasi tahapan siklus hidup proyek ML dan risiko kebocoran data."],
        "concepts": [
            ("Apa yang dipelajari mesin", "Model pembelajaran mesin membangun pemetaan dari masukan ke keluaran berdasarkan pola pada contoh. Sistem tidak sekadar menghafal; ia diharapkan melakukan generalisasi pada data yang belum pernah dilihat. Karena itu, kualitas pengujian sama pentingnya dengan kualitas pelatihan."),
            ("Tiga keluarga masalah", "Supervised learning menggunakan pasangan fitur dan target untuk regresi atau klasifikasi. Unsupervised learning mencari struktur tanpa label, misalnya kelompok atau representasi berdimensi rendah. Pendekatan lain seperti reinforcement learning berada di luar fokus utama semester ini, tetapi membantu menunjukkan luasnya ruang lingkup ML."),
            ("Alur kerja yang dapat diaudit", "Rumusan masalah, pengumpulan data, eksplorasi, preprocessing, pemisahan data, pemodelan, evaluasi, komunikasi hasil, dan pemantauan merupakan satu rantai. Keputusan pada tahap awal menentukan validitas hasil akhir. Baseline sederhana perlu dibuat sebelum mencoba model kompleks."),
            ("Etika dan konteks", "Prediksi dapat berdampak pada manusia. Mahasiswa harus mencatat sumber data, keterbatasan representasi, potensi bias, konsekuensi kesalahan, serta siapa yang menanggung risiko. Akurasi tinggi tidak otomatis berarti sistem layak digunakan."),
        ],
        "lab": ["Kelompok memilih satu kasus kampus atau masyarakat.", "Nyatakan unit analisis, fitur yang mungkin tersedia, target atau keluaran, pengguna hasil, dan biaya kesalahan.", "Gambarkan alur data dari sumber sampai keputusan.", "Tentukan satu baseline non-ML dan satu risiko etis."],
        "practice": ["Klasifikasikan: prediksi harga rumah, segmentasi pelanggan, dan deteksi spam.", "Mengapa data uji tidak boleh dipakai untuk memilih model?", "Sebutkan dua contoh kebocoran data.", "Apa perbedaan parameter dan hyperparameter?", "Tuliskan satu pertanyaan yang harus dijawab sebelum mengumpulkan data."],
        "next": "Pertemuan 2 memformalkan cara belajar model, istilah fitur-target, dan perbedaan supervised serta unsupervised learning.",
    },
    {
        "n": 2,
        "title": "Konsep Dasar dan Paradigma Pembelajaran",
        "sub": "CPMK031 SubCPMK1",
        "link": "Mengubah kasus pada pertemuan 1 menjadi formulasi matematis dan rancangan eksperimen.",
        "goals": ["Menggunakan istilah sampel, fitur, label, model, loss, dan generalisasi secara tepat.", "Memilih paradigma belajar sesuai bentuk data dan tujuan.", "Menjelaskan underfitting dan overfitting melalui bias dan varians."],
        "concepts": [
            ("Representasi data", "Dataset tabular dapat dinyatakan sebagai matriks X berukuran n × p, dengan n sampel dan p fitur. Untuk supervised learning tersedia vektor target y. Model f memetakan X ke prediksi y-hat; fungsi loss mengukur ketidaksesuaian prediksi terhadap target."),
            ("Belajar dari sampel", "Empirical risk adalah rata-rata loss pada data pelatihan. Tujuan nyata bukan meminimalkan loss pelatihan semata, tetapi memperoleh expected risk yang kecil pada populasi. Data validasi membantu memilih konfigurasi, sedangkan data uji digunakan satu kali untuk estimasi akhir."),
            ("Bias dan varians", "Model terlalu sederhana memiliki bias tinggi dan gagal menangkap pola; model terlalu fleksibel dapat memiliki varians tinggi dan peka pada noise. Kompleksitas model, jumlah data, regularisasi, dan validasi mengatur kompromi ini."),
            ("Skala pengukuran", "Fitur nominal, ordinal, interval, dan rasio memerlukan perlakuan berbeda. Jarak Euclidean pada KNN tidak bermakna untuk kode kategori yang diperlakukan sebagai angka. Pemahaman tipe data mendahului pemilihan algoritma."),
        ],
        "lab": ["Tinjau tiga dataset kecil: numerik, kategorikal, dan campuran.", "Tentukan X, y, unit analisis, tugas ML, serta metrik awal.", "Buat sketsa kurva error pelatihan dan validasi terhadap kompleksitas model.", "Diskusikan gejala underfitting dan overfitting."],
        "practice": ["Bedakan loss dan metric menggunakan satu contoh.", "Mengapa error pelatihan bukan ukuran generalisasi?", "Apakah clustering selalu menghasilkan kelompok yang bermakna? Jelaskan.", "Berikan contoh fitur ordinal dan cara representasinya.", "Jika train score 99% dan test score 68%, diagnosis awal apa yang paling masuk akal?"],
        "next": "Pertemuan 3 menyiapkan data sehingga formulasi ini dapat diproses model tanpa kebocoran.",
    },
    {
        "n": 3,
        "title": "Dataset dan Preprocessing yang Aman",
        "sub": "CPMK032 SubCPMK2",
        "link": "Menerapkan pemisahan data sebelum semua transformasi yang belajar dari data.",
        "goals": ["Melakukan audit kualitas data.", "Membangun pipeline imputasi, encoding, dan scaling.", "Mencegah data leakage melalui fit hanya pada data latih."],
        "concepts": [
            ("Audit sebelum transformasi", "Periksa ukuran data, tipe kolom, duplikasi, nilai hilang, rentang tidak wajar, distribusi target, ketidakseimbangan kelas, dan identitas yang berpotensi bocor. Catat setiap keputusan agar eksperimen dapat direproduksi."),
            ("Missing value", "Hilangnya data dapat bersifat acak atau berkaitan dengan proses pengumpulan. Imputasi median tahan terhadap pencilan untuk numerik; modus atau kategori khusus dapat dipakai untuk kategorikal. Indikator missing dapat membawa informasi, tetapi juga berisiko mengkodekan proses yang berubah saat deployment."),
            ("Encoding dan scaling", "One-hot encoding sesuai untuk kategori nominal. Standardisasi menghasilkan rata-rata nol dan simpangan baku satu; penting bagi metode berbasis jarak atau optimisasi gradien. Decision tree umumnya tidak memerlukan scaling."),
            ("Pipeline", "Pipeline mengikat preprocessing dan estimator sehingga prosedur yang sama diterapkan pada train, validasi, dan data baru. Seluruh statistik seperti median dan mean harus dipelajari dari fold pelatihan, bukan dari keseluruhan dataset."),
        ],
        "lab": ["Gunakan dataset campuran dengan nilai hilang.", "Bagi train-test secara stratified bila target berupa kelas.", "Bangun ColumnTransformer untuk fitur numerik dan kategorikal.", "Tampilkan bentuk data sebelum dan sesudah transformasi serta catat risiko leakage."],
        "practice": ["Mengapa scaling sebelum train-test split berbahaya?", "Kapan median lebih tepat daripada mean?", "Apa dampak duplikasi sampel pada evaluasi?", "Jelaskan fungsi stratify.", "Rancang preprocessing untuk umur, kota, penghasilan, dan target gagal bayar."],
        "next": "Pipeline hasil pertemuan ini menjadi masukan yang konsisten untuk regresi linear pada pertemuan 4.",
        "code": """from sklearn.model_selection import train_test_split\nfrom sklearn.compose import ColumnTransformer\nfrom sklearn.pipeline import Pipeline\nfrom sklearn.impute import SimpleImputer\nfrom sklearn.preprocessing import OneHotEncoder, StandardScaler\n\nX_train, X_test, y_train, y_test = train_test_split(\n    X, y, test_size=0.2, random_state=42\n)\nnum_pipe = Pipeline([('impute', SimpleImputer(strategy='median')),\n                     ('scale', StandardScaler())])\ncat_pipe = Pipeline([('impute', SimpleImputer(strategy='most_frequent')),\n                     ('encode', OneHotEncoder(handle_unknown='ignore'))])\nprep = ColumnTransformer([('num', num_pipe, num_cols),\n                          ('cat', cat_pipe, cat_cols)])""",
    },
    {
        "n": 4,
        "title": "Regresi Linear dari Asumsi ke Prediksi",
        "sub": "CPMK032 SubCPMK2",
        "link": "Menggunakan pipeline data untuk memprediksi target kontinu dan membangun baseline yang dapat dijelaskan.",
        "goals": ["Menjelaskan fungsi regresi linear dan koefisiennya.", "Melatih model dalam pipeline.", "Memeriksa residual dan keterbatasan interpretasi."],
        "concepts": [
            ("Model linear", "Regresi linear memodelkan y-hat = b0 + b1x1 + ... + bpxp. Koefisien menyatakan perubahan prediksi untuk kenaikan satu unit fitur ketika fitur lain tetap, bukan otomatis hubungan sebab-akibat."),
            ("Least squares", "Koefisien dipilih dengan meminimalkan jumlah kuadrat residual. Kuadrat memberi penalti besar pada kesalahan ekstrem sehingga model sensitif terhadap pencilan. Baseline mean predictor perlu dibandingkan agar manfaat model terlihat."),
            ("Asumsi untuk inferensi dan diagnosis", "Linearitas, independensi, homoskedastisitas, dan bentuk residual membantu interpretasi. Untuk prediksi, fokus utama tetap kinerja pada data baru, tetapi pola residual dapat mengungkap non-linearitas dan variabel yang terlewat."),
            ("Regularisasi singkat", "Ridge menambahkan penalti kuadrat koefisien dan membantu ketika fitur berkorelasi. Lasso menggunakan penalti absolut dan dapat menghasilkan koefisien nol. Keduanya memerlukan scaling agar penalti adil."),
        ],
        "lab": ["Tentukan baseline menggunakan DummyRegressor.", "Latih LinearRegression pada pipeline pertemuan 3.", "Bandingkan prediksi dan nilai aktual.", "Plot residual terhadap prediksi lalu tulis dua temuan."],
        "practice": ["Interpretasikan koefisien umur sebesar 1,8 pada konteks yang dipilih.", "Mengapa korelasi tinggi antarfitur menyulitkan interpretasi?", "Apa arti residual positif?", "Kapan transformasi log target berguna?", "Mengapa baseline wajib dilaporkan?"],
        "next": "Pertemuan 5 mengukur seberapa baik prediksi regresi dan apakah model benar-benar lebih baik dari baseline.",
        "code": """from sklearn.pipeline import Pipeline\nfrom sklearn.linear_model import LinearRegression\nfrom sklearn.dummy import DummyRegressor\n\nmodel = Pipeline([('prep', prep), ('reg', LinearRegression())])\nmodel.fit(X_train, y_train)\ny_pred = model.predict(X_test)""",
    },
    {
        "n": 5,
        "title": "Evaluasi Model Regresi",
        "sub": "CPMK032 SubCPMK2",
        "link": "Menilai baseline dan regresi dengan metrik yang sesuai konteks serta analisis residual.",
        "goals": ["Menghitung dan menginterpretasi MAE, MSE, RMSE, dan R².", "Memilih metrik berdasarkan biaya kesalahan.", "Membandingkan model tanpa menyalahgunakan data uji."],
        "concepts": [
            ("MAE", "Mean Absolute Error berada pada satuan target dan relatif mudah dijelaskan. Semua kesalahan diberi bobot linear sehingga MAE lebih tahan terhadap pencilan daripada RMSE."),
            ("MSE dan RMSE", "MSE merata-ratakan kuadrat kesalahan; RMSE mengembalikannya ke satuan target. Keduanya menekankan kesalahan besar, sesuai ketika kegagalan ekstrem sangat mahal."),
            ("R kuadrat", "R² membandingkan model dengan prediktor mean pada data yang sama. Nilai 1 berarti sempurna; 0 setara baseline mean; nilai negatif berarti lebih buruk. R² tidak menjelaskan besar kesalahan dalam satuan dunia nyata."),
            ("Analisis per segmen", "Rata-rata keseluruhan dapat menyembunyikan kegagalan pada kelompok tertentu. Evaluasi per rentang target atau kelompok relevan diperlukan, dengan kehati-hatian agar ukuran sampel cukup."),
        ],
        "lab": ["Hitung MAE, RMSE, dan R² untuk baseline dan model.", "Buat tabel error per kuartil target.", "Identifikasi lima prediksi dengan error absolut terbesar.", "Tulis keputusan: layak sebagai baseline berikutnya, perlu revisi, atau tidak layak."],
        "practice": ["Model A MAE 4 dan RMSE 12; apa indikasinya?", "Mengapa R² tinggi belum tentu berguna?", "Pilih metrik untuk prediksi waktu tunggu pasien dan jelaskan.", "Apa arti R² negatif?", "Bagaimana menguji apakah satu fitur baru benar-benar membantu?"],
        "next": "Pertemuan 6 beralih dari target kontinu ke klasifikasi menggunakan KNN dan decision tree.",
    },
    {
        "n": 6,
        "title": "KNN dan Decision Tree untuk Klasifikasi",
        "sub": "CPMK032 SubCPMK2",
        "link": "Memanfaatkan pipeline dan disiplin evaluasi untuk dua model dengan bias induktif berbeda.",
        "goals": ["Menjelaskan mekanisme KNN dan decision tree.", "Menghubungkan scaling, nilai k, dan kompleksitas pohon dengan generalisasi.", "Membandingkan model terhadap baseline kelas mayoritas."],
        "concepts": [
            ("KNN", "KNN memprediksi kelas berdasarkan mayoritas tetangga terdekat. Nilai k kecil menghasilkan batas keputusan fleksibel tetapi peka noise; k besar lebih halus dan dapat mengaburkan kelompok lokal. Scaling sangat penting karena jarak didominasi fitur berskala besar."),
            ("Decision tree", "Pohon memilih split yang mengurangi impurity, misalnya Gini. Kedalaman dan jumlah minimum sampel per daun mengontrol kompleksitas. Pohon mudah dijelaskan tetapi tidak stabil terhadap perubahan kecil pada data."),
            ("Ketidakseimbangan kelas", "Akurasi dapat menyesatkan ketika kelas mayoritas dominan. Gunakan stratified split, confusion matrix, precision, recall, dan F1 sesuai biaya false positive dan false negative."),
            ("Perbandingan adil", "Model dibandingkan pada split yang sama dan pipeline yang benar. Jangan memilih hyperparameter berdasarkan test score; gunakan validasi pada pertemuan 10."),
        ],
        "lab": ["Latih DummyClassifier, KNN, dan DecisionTreeClassifier.", "Uji k = 3, 5, 9 sebagai eksplorasi terbatas pada data validasi.", "Visualisasikan pohon dangkal.", "Bandingkan waktu prediksi dan pola kesalahan."],
        "practice": ["Mengapa KNN disebut lazy learner?", "Apa akibat fitur pendapatan tidak diskalakan?", "Kapan pohon lebih cocok daripada KNN?", "Apa fungsi max_depth?", "Mengapa akurasi 95% dapat buruk pada prevalensi positif 2%?"],
        "next": "Pertemuan 7 menghapus label dan mencari struktur melalui K-Means.",
        "code": """from sklearn.neighbors import KNeighborsClassifier\nfrom sklearn.tree import DecisionTreeClassifier\n\nknn = Pipeline([('prep', prep), ('model', KNeighborsClassifier(n_neighbors=5))])\ntree = Pipeline([('prep', prep), ('model', DecisionTreeClassifier(\n    max_depth=4, random_state=42))])""",
    },
    {
        "n": 7,
        "title": "Clustering dengan K Means",
        "sub": "CPMK081 SubCPMK3",
        "link": "Memperluas pemodelan dari prediksi berlabel ke penemuan struktur tanpa label.",
        "goals": ["Menjelaskan objective K-Means dan proses iteratifnya.", "Memilih fitur serta scaling yang relevan.", "Menilai cluster dengan inertia, silhouette, stabilitas, dan interpretasi domain."],
        "concepts": [
            ("Tujuan K-Means", "K-Means meminimalkan jumlah kuadrat jarak sampel ke centroid kelompok. Algoritma bergantian antara assignment ke centroid terdekat dan pembaruan centroid hingga konvergen."),
            ("Asumsi geometris", "K-Means cenderung cocok untuk cluster kompak, kurang lebih bulat, dan berskala serupa. Ia sensitif terhadap scaling, pencilan, inisialisasi, dan pilihan k."),
            ("Evaluasi tanpa label", "Inertia selalu menurun saat k bertambah sehingga elbow hanya petunjuk. Silhouette membandingkan kedekatan internal dan pemisahan antarcluster. Validitas utama tetap kegunaan dan interpretasi di konteks masalah."),
            ("Profiling cluster", "Setelah cluster terbentuk, ringkas distribusi fitur untuk memberi makna. Label seperti kelompok 0 tidak bermakna sampai dihubungkan dengan karakteristik dan keterbatasan."),
        ],
        "lab": ["Pilih 3-6 fitur numerik yang bermakna.", "Standardisasi fitur dan uji k = 2 sampai 6.", "Plot inertia dan silhouette.", "Buat profil cluster dan jelaskan satu risiko salah tafsir."],
        "practice": ["Mengapa elbow bukan bukti tunggal nilai k?", "Apa akibat pencilan ekstrem?", "Apakah cluster sama dengan kelas alami?", "Interpretasikan silhouette mendekati nol.", "Sebutkan kasus ketika DBSCAN mungkin lebih tepat."],
        "next": "UTS pada pertemuan 8 menguji pemahaman terpadu dari formulasi masalah sampai clustering.",
    },
    {
        "n": 8,
        "title": "Ujian Tengah Semester",
        "sub": "Evaluasi SubCPMK1 hingga SubCPMK3 awal",
        "link": "Mengintegrasikan formulasi masalah, preprocessing, regresi, klasifikasi, dan clustering.",
        "goals": ["Menunjukkan penguasaan konsep tanpa bergantung pada hafalan.", "Mendiagnosis kesalahan pipeline.", "Menginterpretasi keluaran model dan metrik."],
        "concepts": [("Format", "Ujian terdiri dari analisis konsep dan studi kasus praktis selama 100 menit. Soal lengkap terdapat pada bagian Penilaian Semester. Mahasiswa boleh membawa satu lembar ringkasan A4; komunikasi antarpeserta tidak diperkenankan.")],
        "lab": ["10 menit membaca dan memilih urutan pengerjaan.", "55 menit bagian konsep dan diagnosis.", "30 menit studi kasus.", "5 menit pemeriksaan jawaban."],
        "practice": ["Gunakan peta konsep pertemuan 1-7.", "Latih interpretasi metrik, bukan hanya rumus.", "Periksa urutan split, fit, transform, predict, evaluate.", "Siapkan alasan untuk setiap pilihan model.", "Tuliskan asumsi jika informasi soal terbatas."],
        "next": "Pertemuan 9 memulai fase kedua dan proyek akhir dengan reduksi dimensi menggunakan PCA.",
    },
    {
        "n": 9,
        "title": "PCA dan Reduksi Dimensi",
        "sub": "CPMK081 SubCPMK3",
        "link": "Meringkas fitur numerik berkorelasi dan memulai eksplorasi dataset proyek akhir.",
        "goals": ["Menjelaskan principal component sebagai kombinasi linear fitur.", "Memilih jumlah komponen berdasarkan explained variance dan tujuan.", "Mencegah leakage ketika PCA digunakan dalam pipeline."],
        "concepts": [
            ("Arah variasi", "PCA mencari arah ortogonal yang menangkap variasi terbesar secara berurutan. Principal component bukan fitur asli, melainkan kombinasi berbobot fitur yang telah dipusatkan, biasanya juga diskalakan."),
            ("Explained variance", "Explained variance ratio menunjukkan proporsi variasi yang ditangkap setiap komponen. Ambang kumulatif seperti 90% adalah titik awal, bukan aturan universal; performa downstream dan interpretabilitas juga dipertimbangkan."),
            ("Kapan berguna", "PCA dapat membantu visualisasi, kompresi, pengurangan kolinearitas, dan percepatan model tertentu. PCA tidak otomatis meningkatkan akurasi dan dapat mengaburkan interpretasi fitur."),
            ("PCA dalam pipeline", "Scaler dan PCA di-fit hanya pada data latih atau fold pelatihan. Jika di-fit sebelum cross-validation, informasi distribusi validasi bocor ke representasi."),
        ],
        "lab": ["Pilih dataset numerik dengan lebih dari lima fitur.", "Bandingkan visualisasi dua fitur asli dan dua komponen utama.", "Plot cumulative explained variance.", "Bandingkan model dengan dan tanpa PCA."],
        "practice": ["Mengapa scaling penting sebelum PCA?", "Apakah PC1 selalu fitur paling penting bagi target?", "Apa arti explained variance 70%?", "Mengapa PCA sulit diinterpretasi?", "Letakkan PCA pada urutan pipeline yang benar."],
        "next": "Pertemuan 10 memastikan semua perbandingan model dilakukan dengan strategi validasi yang sahih.",
    },
    {
        "n": 10,
        "title": "Validasi Model dan Generalisasi",
        "sub": "CPMK082 SubCPMK3",
        "link": "Mengubah eksperimen tunggal menjadi estimasi kinerja yang lebih stabil.",
        "goals": ["Membedakan train, validation, dan test set.", "Menerapkan k-fold dan stratified k-fold cross-validation.", "Mendiagnosis overfitting dengan learning curve."],
        "concepts": [
            ("Peran tiga himpunan", "Train digunakan untuk mempelajari parameter, validation untuk memilih model dan hyperparameter, sedangkan test untuk estimasi akhir. Penggunaan test berulang mengubahnya secara terselubung menjadi validation set."),
            ("Cross-validation", "Dalam k-fold CV, data dibagi menjadi k fold; setiap fold bergiliran menjadi validasi. Rata-rata dan simpangan baku skor dilaporkan. StratifiedKFold menjaga proporsi kelas pada klasifikasi."),
            ("Skema khusus", "Data waktu memerlukan pemisahan kronologis; data pasien atau pengguna berulang memerlukan GroupKFold agar identitas tidak muncul di train dan validasi. Skema validasi harus meniru cara model dipakai."),
            ("Learning curve", "Gap besar antara train dan validation score menunjukkan varians tinggi. Skor keduanya rendah menunjukkan bias tinggi. Kurva terhadap jumlah data membantu memutuskan apakah menambah data berpotensi membantu."),
        ],
        "lab": ["Bandingkan satu split dengan 5-fold CV.", "Laporkan mean ± standard deviation.", "Pilih skema CV sesuai dataset proyek.", "Buat learning curve dan rekomendasi tindakan."],
        "practice": ["Mengapa data waktu tidak boleh diacak sembarang?", "Apa fungsi GroupKFold?", "Interpretasikan CV 0,81 ± 0,14.", "Mengapa pipeline wajib saat CV?", "Kapan holdout lebih praktis daripada CV?"],
        "next": "Pertemuan 11 memperdalam evaluasi klasifikasi agar pemilihan model sesuai biaya kesalahan.",
    },
    {
        "n": 11,
        "title": "Evaluasi Klasifikasi dan Ambang Keputusan",
        "sub": "CPMK082 SubCPMK3",
        "link": "Menghubungkan probabilitas prediksi dengan konsekuensi false positive dan false negative.",
        "goals": ["Membaca confusion matrix secara benar.", "Menghitung precision, recall, specificity, dan F1.", "Menilai ROC-AUC, PR-AUC, probabilitas, dan threshold."],
        "concepts": [
            ("Confusion matrix", "True positive dan true negative adalah keputusan benar; false positive dan false negative adalah dua tipe kesalahan dengan biaya berbeda. Orientasi baris-kolom harus selalu diperiksa sebelum interpretasi."),
            ("Precision dan recall", "Precision menjawab proporsi prediksi positif yang benar; recall menjawab proporsi kasus positif yang berhasil ditemukan. F1 adalah harmonic mean yang berguna ketika keduanya perlu diseimbangkan."),
            ("Kurva dan ranking", "ROC-AUC mengukur kemampuan ranking lintas threshold, tetapi dapat tampak optimistis pada kelas sangat langka. PR-AUC lebih fokus pada kelas positif dan perlu dibandingkan dengan prevalensi dasar."),
            ("Threshold dan kalibrasi", "Threshold 0,5 bukan hukum. Pilih threshold berdasarkan biaya, kapasitas tindak lanjut, atau target recall. Probabilitas terkalibrasi berarti prediksi 0,8 benar sekitar 80% pada kelompok serupa."),
        ],
        "lab": ["Hitung confusion matrix dan metrik pada threshold 0,5.", "Ubah threshold menjadi 0,3 dan 0,7.", "Plot ROC dan precision-recall curve.", "Pilih threshold berdasarkan skenario biaya yang diberikan."],
        "practice": ["Pada skrining penyakit, kapan recall diprioritaskan?", "Hitung precision dari TP=40 dan FP=10.", "Mengapa specificity tetap penting?", "Apa baseline PR-AUC?", "Jelaskan beda diskriminasi dan kalibrasi."],
        "next": "Pertemuan 12 mengoptimalkan hyperparameter menggunakan validasi tanpa menyentuh test set.",
    },
    {
        "n": 12,
        "title": "Optimisasi dan Hyperparameter Tuning",
        "sub": "CPMK083 SubCPMK4",
        "link": "Membuat pemilihan konfigurasi sistematis dan dapat direproduksi.",
        "goals": ["Membedakan parameter dan hyperparameter.", "Menerapkan GridSearchCV atau RandomizedSearchCV dalam pipeline.", "Melaporkan hasil tuning tanpa klaim berlebihan."],
        "concepts": [
            ("Ruang pencarian", "Grid search mencoba seluruh kombinasi yang ditentukan; random search mengambil sampel kombinasi dan sering lebih efisien ketika hanya sebagian hyperparameter berpengaruh kuat."),
            ("Scoring", "Metrik tuning harus mencerminkan tujuan. Pada kelas tidak seimbang, accuracy jarang tepat. Multi-metric evaluation dapat digunakan, tetapi satu aturan refit harus ditetapkan."),
            ("Nested logic", "Search melakukan CV internal untuk memilih konfigurasi. Test set tetap disimpan untuk evaluasi akhir. Untuk estimasi yang sangat ketat, nested CV memisahkan pemilihan dan evaluasi pada loop berbeda."),
            ("Biaya komputasi", "Jumlah kandidat × jumlah fold × waktu fit menentukan biaya. Batasi rentang secara beralasan, gunakan random search, dan mulai dari subset kecil untuk memeriksa pipeline."),
        ],
        "lab": ["Tentukan grid kecil untuk KNN atau decision tree.", "Jalankan search dengan 5-fold CV dan metrik utama.", "Tampilkan lima konfigurasi terbaik beserta variabilitasnya.", "Evaluasi best estimator satu kali pada test set."],
        "practice": ["Hitung jumlah fit untuk 12 kandidat dan 5 fold.", "Mengapa memilih dari test score menyebabkan optimisme?", "Kapan random search lebih baik?", "Apa arti best_params_?", "Mengapa hasil terbaik perlu dibandingkan dengan baseline?"],
        "next": "Pertemuan 13 memperkenalkan jaringan saraf sebagai model berlapis dengan optimisasi gradien.",
        "code": """from sklearn.model_selection import GridSearchCV\n\nparams = {'model__max_depth': [3, 5, 8, None],\n          'model__min_samples_leaf': [1, 5, 10]}\nsearch = GridSearchCV(tree_pipeline, params, cv=5,\n                      scoring='f1', n_jobs=-1)\nsearch.fit(X_train, y_train)""",
    },
    {
        "n": 13,
        "title": "Perceptron dan Jaringan Saraf Dasar",
        "sub": "CPMK084 SubCPMK4",
        "link": "Menggeneralisasi model linear menjadi komposisi transformasi berlapis.",
        "goals": ["Menjelaskan neuron, bobot, bias, aktivasi, loss, dan epoch.", "Menggambarkan forward pass dan pembaruan berbasis gradien.", "Mengenali overfitting dan keterbatasan jaringan kecil."],
        "concepts": [
            ("Perceptron", "Neuron menghitung kombinasi linear z = w·x + b lalu menerapkan aktivasi. Tanpa aktivasi non-linear, menumpuk beberapa lapisan tetap setara dengan satu transformasi linear."),
            ("Training", "Forward pass menghasilkan prediksi dan loss. Backpropagation menghitung gradien loss terhadap parameter; optimizer memperbarui parameter dengan learning rate tertentu. Epoch adalah satu lintasan penuh melalui data latih."),
            ("Kapasitas dan regularisasi", "Lebih banyak unit meningkatkan kapasitas dan risiko overfitting. Early stopping, regularisasi bobot, dropout, serta data yang memadai membantu generalisasi. Scaling fitur mendukung optimisasi stabil."),
            ("Penggunaan yang proporsional", "Dataset tabular kecil sering tidak membutuhkan deep learning. Model sederhana dapat lebih cepat, lebih transparan, dan sama baiknya. Kompleksitas harus dibenarkan oleh kebutuhan dan bukti."),
        ],
        "lab": ["Latih MLPClassifier kecil dengan satu hidden layer.", "Plot training loss dan validation score.", "Bandingkan dengan decision tree atau regresi logistik.", "Catat biaya waktu, stabilitas, dan interpretabilitas."],
        "practice": ["Mengapa aktivasi non-linear diperlukan?", "Apa dampak learning rate terlalu besar?", "Bedakan batch dan epoch.", "Bagaimana early stopping bekerja?", "Mengapa neural network bukan pilihan otomatis?"],
        "next": "Pertemuan 14 mengintegrasikan seluruh pipeline menjadi proyek yang dapat direproduksi.",
    },
    {
        "n": 14,
        "title": "Implementasi Proyek Pembelajaran Mesin",
        "sub": "CPMK084 SubCPMK4",
        "link": "Menggabungkan perumusan masalah, data, model, validasi, evaluasi, dan komunikasi.",
        "goals": ["Membangun proyek end-to-end yang dapat dijalankan ulang.", "Membandingkan minimal dua algoritma secara adil.", "Menulis model card ringkas dan analisis keterbatasan."],
        "concepts": [
            ("Struktur proyek", "Pisahkan data mentah, notebook eksplorasi, kode preprocessing/model, output, dan laporan. Tetapkan random_state, versi library, serta petunjuk menjalankan ulang."),
            ("Evidence chain", "Setiap klaim harus terhubung ke tabel, gambar, metrik, atau analisis error. Hindari menyatakan model baik hanya karena satu skor tinggi."),
            ("Perbandingan model", "Gunakan split, preprocessing, skema CV, dan metrik yang sama. Jelaskan mengapa dua model dipilih serta kompromi akurasi, waktu, interpretabilitas, dan risiko."),
            ("Model card", "Dokumentasikan tujuan, data, metrik, kelompok pengguna, batas penggunaan, potensi bias, dan langkah pemantauan. Ini mengubah notebook menjadi artefak yang dapat dipertanggungjawabkan."),
        ],
        "lab": ["Lakukan code review antarkelompok memakai checklist.", "Pastikan seluruh preprocessing berada dalam pipeline.", "Buat tabel baseline versus dua model.", "Selesaikan model card dan daftar keterbatasan."],
        "practice": ["Apa syarat eksperimen dapat direproduksi?", "Mengapa notebook yang hanya berjalan dari state tertentu bermasalah?", "Apa isi minimum model card?", "Bagaimana melaporkan hasil negatif?", "Sebutkan tiga bukti yang harus mendukung rekomendasi model."],
        "next": "Pertemuan 15 menguji kemampuan menyampaikan dan mempertahankan keputusan teknis.",
    },
    {
        "n": 15,
        "title": "Presentasi dan Telaah Mini Project",
        "sub": "CPMK084 SubCPMK4",
        "link": "Mengubah pekerjaan teknis menjadi argumen yang ringkas, transparan, dan dapat diuji.",
        "goals": ["Menyajikan alur masalah-data-model-evaluasi-keputusan.", "Menjawab pertanyaan tentang validitas dan keterbatasan.", "Memberi umpan balik sejawat yang spesifik."],
        "concepts": [
            ("Narasi presentasi", "Mulai dari keputusan atau masalah, bukan daftar library. Jelaskan dataset dan split, tampilkan baseline, bandingkan model, tunjukkan analisis error, lalu akhiri dengan rekomendasi dan batas penggunaan."),
            ("Visual yang jujur", "Gunakan label sumbu, satuan, ukuran sampel, dan baseline. Hindari sumbu terpotong yang menyesatkan atau memilih hanya grafik yang mendukung model favorit."),
            ("Pertahanan teknis", "Jawaban yang baik mengakui ketidakpastian, menyebut bukti, dan membedakan apa yang diketahui dari dugaan. Jika ada kelemahan, jelaskan dampak dan rencana perbaikan."),
        ],
        "lab": ["Presentasi 8 menit per kelompok.", "Demo 3 menit yang menjalankan prediksi atau pipeline.", "Tanya jawab 5 menit.", "Peer review menggunakan dua kekuatan, satu risiko, dan satu saran terukur."],
        "practice": ["Ringkas proyek dalam 60 detik.", "Jawab: mengapa model ini dipilih?", "Jawab: bagaimana Anda mencegah leakage?", "Jawab: kelompok mana yang mungkin dirugikan?", "Jawab: eksperimen lanjutan apa yang paling bernilai?"],
        "next": "Pertemuan 16 menutup semester dengan evaluasi UAS, refleksi individual, dan pembuktian kontribusi.",
    },
    {
        "n": 16,
        "title": "Ujian Akhir Semester dan Refleksi",
        "sub": "Evaluasi SubCPMK4",
        "link": "Menilai produk kelompok sekaligus penguasaan individual atas keseluruhan pipeline.",
        "goals": ["Menyerahkan artefak proyek lengkap.", "Mempertahankan keputusan teknis secara individual.", "Merefleksikan keterbatasan dan rencana perbaikan."],
        "concepts": [("Format", "UAS berbasis proyek terdiri atas kualitas solusi dan reproduktibilitas, laporan, presentasi-demo, serta viva/refleksi individual. Rincian bobot dan rubrik terdapat pada bagian Penilaian Semester.")],
        "lab": ["Pengumpulan notebook, laporan, slide, README, dan model card.", "Pemeriksaan reproduktibilitas dengan menjalankan ulang bagian inti.", "Viva individual 5-7 menit.", "Refleksi tertulis tentang keputusan, kegagalan, dan perbaikan."],
        "practice": ["Pastikan sumber data dan lisensi tercantum.", "Pastikan notebook berjalan dari awal.", "Pisahkan hasil validasi dan test.", "Laporkan baseline dan variasi skor.", "Nyatakan batas penggunaan model."],
        "next": "Hasil semester menjadi portofolio awal yang dapat dikembangkan pada mata kuliah AI, data mining, atau tugas akhir.",
    },
]


course_map = [(m["n"], m["title"], m["sub"]) for m in modules]


def build_student_module():
    doc = Document()
    configure_document(doc, "Modul Mahasiswa")
    add_page_number(doc.sections[0].footer.paragraphs[0])
    add_cover(doc, "Modul Pembelajaran Mesin", "Materi, aktivitas, latihan, tugas terstruktur, UTS, dan UAS")

    doc.add_heading("Cara Menggunakan Modul", level=1)
    add_para(doc, "Modul ini menuntun pembelajaran selama 16 pertemuan. Setiap topik memakai artefak dari pertemuan sebelumnya sehingga mahasiswa membangun satu alur kerja utuh: merumuskan masalah, menyiapkan data, melatih model, menguji generalisasi, mengoptimalkan, lalu mempertanggungjawabkan hasil.")
    add_bullets(doc, ["Sebelum kelas: baca tujuan dan konsep inti.", "Saat kelas: kerjakan aktivitas terbimbing dalam kelompok kecil.", "Setelah kelas: selesaikan latihan dan simpan artefaknya dalam repositori belajar.", "Untuk proyek: gunakan dataset publik yang legal, cantumkan sumber, dan jangan memasukkan data pribadi tanpa izin."])

    doc.add_heading("Capaian dan Penilaian", level=1)
    add_table(doc, ["Komponen", "Bobot", "Bukti"], [
        ("Tugas terstruktur 1-5", "25%", "Notebook atau laporan ringkas, masing-masing 5%"),
        ("Kehadiran dan partisipasi", "10%", "Kehadiran, kesiapan, kontribusi aktivitas"),
        ("UTS", "25%", "Konsep, diagnosis pipeline, dan studi kasus"),
        ("UAS", "40%", "Proyek akhir, laporan, presentasi, dan viva individual"),
    ], [5.0, 2.6, 9.5])
    add_table(doc, ["SubCPMK", "Capaian"], [
        ("1", "Memahami konsep dasar dan ruang lingkup pembelajaran mesin."),
        ("2", "Mengidentifikasi dan mengimplementasikan algoritma supervised learning."),
        ("3", "Menerapkan algoritma unsupervised learning dan evaluasi model."),
        ("4", "Mendesain dan mengevaluasi sistem pembelajaran mesin berbasis proyek."),
    ], [2.8, 14.3])

    doc.add_heading("Peta Pertemuan", level=1)
    add_table(doc, ["Ke", "Topik", "Keterkaitan CPMK"], course_map, [1.5, 10.5, 5.2])

    for m in modules:
        doc.add_heading(f"Pertemuan {m['n']}  {m['title']}", level=1)
        p = doc.add_paragraph()
        r = p.add_run(m["sub"])
        r.bold = True
        r.font.color.rgb = RGBColor(23, 54, 93)
        add_para(doc, m["link"])
        doc.add_heading("Capaian Pertemuan", level=2)
        add_bullets(doc, m["goals"])
        doc.add_heading("Konsep Inti", level=2)
        for h, text in m["concepts"]:
            doc.add_heading(h, level=3)
            add_para(doc, text)
        if m.get("code"):
            doc.add_heading("Contoh Implementasi", level=2)
            add_code(doc, m["code"])
        doc.add_heading("Aktivitas Terbimbing", level=2)
        add_numbered(doc, m["lab"])
        doc.add_heading("Latihan Mandiri", level=2)
        add_numbered(doc, m["practice"])
        doc.add_heading("Artefak yang Disimpan", level=2)
        add_para(doc, "Simpan notebook, tabel hasil, grafik, dan keputusan singkat pada folder pertemuan. Beri nama file konsisten dan catat random seed, sumber data, serta versi eksperimen.")
        doc.add_heading("Jembatan ke Pertemuan Berikutnya", level=2)
        add_para(doc, m["next"])

    doc.add_page_break()
    doc.add_heading("Tugas Terstruktur", level=1)
    assignments = [
        ("TT1 Audit Data dan Pipeline", "Minggu 3", "Audit dataset, data dictionary, split, dan pipeline preprocessing tanpa leakage.", "Notebook + memo 2 halaman", "5%"),
        ("TT2 Regresi dan Evaluasi", "Minggu 5", "Baseline, regresi linear, MAE, RMSE, R², dan analisis residual.", "Notebook + tabel hasil", "5%"),
        ("TT3 Klasifikasi Komparatif", "Minggu 6", "Bandingkan baseline, KNN, dan decision tree pada split yang sama.", "Notebook + ringkasan 800 kata", "5%"),
        ("TT4 Clustering dan PCA", "Minggu 9", "Profil cluster, evaluasi silhouette, visualisasi PCA, dan batas interpretasi.", "Notebook + 3 visual", "5%"),
        ("TT5 Validasi dan Tuning", "Minggu 12", "Cross-validation, tuning, test akhir, dan catatan biaya komputasi.", "Notebook + model card ringkas", "5%"),
    ]
    add_table(doc, ["Tugas", "Waktu", "Inti Pekerjaan", "Luaran", "Bobot"], assignments, [3.2, 2.1, 6.4, 3.8, 1.5], 8.4)
    doc.add_heading("Ketentuan Umum Tugas", level=2)
    add_bullets(doc, ["Gunakan dataset publik atau data yang memiliki izin penggunaan.", "Notebook harus dapat dijalankan dari awal tanpa state tersembunyi.", "Semua transformasi yang belajar dari data dilakukan setelah split dan berada dalam pipeline.", "Setiap tabel atau visual diikuti interpretasi, bukan hanya ditempel.", "Cantumkan pembagian kontribusi jika tugas berkelompok dan deklarasikan penggunaan alat bantu AI sesuai kebijakan dosen."])
    doc.add_heading("Rubrik Tugas Terstruktur", level=2)
    add_table(doc, ["Kriteria", "Sangat baik", "Baik", "Cukup", "Perlu perbaikan"], [
        ("Kebenaran teknis 35%", "Pipeline benar, tidak ada leakage, hasil dapat dijalankan ulang", "Ada kekurangan minor", "Beberapa keputusan tidak tepat", "Kesalahan mendasar atau tidak berjalan"),
        ("Analisis 30%", "Interpretasi tajam dan terkait konteks", "Interpretasi umumnya benar", "Deskriptif dan dangkal", "Klaim tidak didukung"),
        ("Eksperimen 20%", "Baseline dan perbandingan adil", "Perbandingan cukup", "Kontrol eksperimen lemah", "Tidak ada pembanding"),
        ("Komunikasi 15%", "Ringkas, rapi, visual terbaca, sumber lengkap", "Mayoritas jelas", "Struktur kurang konsisten", "Sulit dibaca atau sumber hilang"),
    ], [3.6, 4.2, 3.5, 3.4, 3.4], 8)

    doc.add_page_break()
    doc.add_heading("Ujian Tengah Semester", level=1)
    add_para(doc, "Waktu 100 menit. Bobot UTS terhadap nilai akhir 25%. Kerjakan mandiri. Tunjukkan alasan dan langkah, karena jawaban tanpa argumentasi memperoleh nilai terbatas.")
    doc.add_heading("Bagian A Konsep dan Diagnosis 40 poin", level=2)
    uts_a = [
        "Jelaskan perbedaan parameter, hyperparameter, dan metrik. Berikan satu contoh untuk decision tree.",
        "Sebuah tim melakukan standardisasi seluruh data, lalu train-test split. Jelaskan masalahnya dan perbaiki urutannya.",
        "Model regresi memperoleh train RMSE 2,1 dan test RMSE 14,8. Berikan dua diagnosis dan dua tindakan.",
        "Dataset fraud memiliki 1% kelas positif. Jelaskan mengapa accuracy tidak cukup dan pilih dua metrik.",
        "Bandingkan KNN dan decision tree dari sisi scaling, waktu prediksi, dan interpretabilitas.",
    ]
    add_numbered(doc, uts_a)
    doc.add_heading("Bagian B Studi Kasus 60 poin", level=2)
    add_para(doc, "Sebuah program studi ingin memprediksi risiko mahasiswa terlambat lulus. Data berisi IP semester, jumlah pengulangan mata kuliah, kehadiran, status beasiswa, asal sekolah, dan status lulus tepat waktu. Terdapat missing value, data tiap mahasiswa muncul pada beberapa baris semester, dan kelas tidak seimbang.")
    add_numbered(doc, [
        "Rumuskan unit analisis, fitur, target, pengguna, dan risiko kesalahan.",
        "Rancang split yang mencegah mahasiswa yang sama muncul di train dan test.",
        "Rancang preprocessing untuk fitur numerik dan kategorikal.",
        "Pilih baseline serta dua model dari materi pertemuan 1-7 dan jelaskan alasannya.",
        "Tentukan metrik utama, metrik pendamping, dan cara menganalisis kesalahan per kelompok.",
        "Jelaskan dua isu etis dan satu batas penggunaan model.",
    ])

    doc.add_page_break()
    doc.add_heading("Ujian Akhir Semester", level=1)
    add_para(doc, "UAS berupa proyek end-to-end berbobot 40% nilai akhir. Proyek dikerjakan kelompok 3-5 orang, tetapi penguasaan individual dinilai melalui viva dan refleksi. Pilih dataset publik dari sumber tepercaya seperti UCI, portal pemerintah, atau Kaggle dan cantumkan lisensinya.")
    add_heading = doc.add_heading
    add_heading("Luaran Wajib", level=2)
    add_bullets(doc, ["Notebook yang berjalan dari awal hingga akhir.", "Laporan PDF 8-12 halaman: masalah, data, metode, hasil, analisis error, keterbatasan, dan rekomendasi.", "README berisi sumber data, struktur folder, dependensi, serta cara menjalankan.", "Slide presentasi maksimal 10 slide.", "Model card satu halaman dan log kontribusi anggota."])
    add_heading("Tahapan", level=2)
    add_table(doc, ["Minggu", "Milestone", "Bukti"], [
        ("9", "Proposal dan audit dataset", "Rumusan masalah, sumber, target, metrik, risiko"),
        ("10", "Skema validasi dan baseline", "Split/CV, baseline, pipeline awal"),
        ("11", "Evaluasi dan analisis error", "Confusion matrix/metric atau residual"),
        ("12", "Tuning", "Ruang pencarian dan hasil CV"),
        ("13", "Model pembanding", "Minimal dua algoritma, boleh termasuk NN sederhana"),
        ("14", "Code review", "Notebook reproducible dan model card"),
        ("15", "Presentasi", "Demo dan tanya jawab kelompok"),
        ("16", "Pengumpulan dan viva", "Artefak final dan refleksi individual"),
    ], [2.0, 6.0, 9.0])
    add_heading("Rubrik UAS", level=2)
    add_table(doc, ["Komponen", "Poin", "Indikator utama"], [
        ("Rumusan masalah dan data", "6", "Tujuan jelas, sumber/legalitas, audit kualitas, risiko"),
        ("Pipeline dan reproduktibilitas", "8", "Split tepat, preprocessing aman, notebook berjalan ulang"),
        ("Pemodelan dan validasi", "8", "Baseline, dua algoritma, CV/tuning adil"),
        ("Evaluasi dan analisis", "8", "Metrik sesuai, analisis error, batas klaim"),
        ("Laporan dan model card", "4", "Argumentasi, visual, referensi, batas penggunaan"),
        ("Presentasi dan demo", "3", "Ringkas, jujur, dapat menjawab pertanyaan"),
        ("Viva dan refleksi individual", "3", "Menguasai kontribusi dan keseluruhan pipeline"),
    ], [6.0, 2.0, 9.0])
    add_heading("Pertanyaan Viva Contoh", level=2)
    add_bullets(doc, ["Tunjukkan di mana leakage dicegah dalam kode.", "Mengapa metrik utama ini dipilih?", "Apa baseline dan seberapa besar perbaikannya?", "Apa satu keputusan yang akan berubah jika dataset bertambah dua kali lipat?", "Kelompok mana yang berisiko menerima performa lebih buruk?", "Jika model gagal setelah deployment, sinyal apa yang pertama dipantau?"])

    doc.add_page_break()
    doc.add_heading("Referensi Utama", level=1)
    refs = [
        "Mitchell, T. M. (1997). Machine Learning. McGraw-Hill.",
        "Alpaydin, E. (2020). Introduction to Machine Learning. MIT Press.",
        "Géron, A. (2022). Hands-On Machine Learning with Scikit-Learn, Keras, and TensorFlow. O'Reilly.",
        "Bishop, C. M. (2006). Pattern Recognition and Machine Learning. Springer.",
        "Hastie, T., Tibshirani, R., & Friedman, J. (2009). The Elements of Statistical Learning. Springer.",
        "Goodfellow, I., Bengio, Y., & Courville, A. (2016). Deep Learning. MIT Press.",
        "Murphy, K. P. (2012). Machine Learning: A Probabilistic Perspective. MIT Press.",
        "Scikit-learn Developers. User Guide, bagian preprocessing, model selection, metrics, clustering, dan decomposition.",
    ]
    add_numbered(doc, refs)
    path = OUT / "Modul_Mahasiswa_Pembelajaran_Mesin_2026-2027.docx"
    doc.save(path)
    return path


def build_teacher_guide():
    doc = Document()
    configure_document(doc, "Panduan Dosen")
    add_page_number(doc.sections[0].footer.paragraphs[0])
    add_cover(doc, "Panduan Dosen Pembelajaran Mesin", "Skenario pengajaran, asesmen, kunci, dan rubrik")
    doc.add_heading("Prinsip Pelaksanaan", level=1)
    add_para(doc, "Panduan ini mendampingi Modul Mahasiswa. Alur semester memakai constructive alignment: setiap pertemuan menghasilkan artefak yang dibutuhkan oleh tugas berikutnya, dan proyek UAS menggunakan kebiasaan kerja yang telah dinilai melalui lima tugas terstruktur.")
    add_bullets(doc, ["Gunakan satu dataset contoh konsisten untuk demonstrasi, lalu dataset berbeda untuk latihan.", "Tampilkan baseline sebelum model kompleks.", "Minta mahasiswa menyebutkan unit analisis, split, dan metrik sebelum membuka kode.", "Nilai penalaran serta reproduktibilitas, bukan hanya skor tertinggi.", "Simpan test set untuk evaluasi akhir dan bahas setiap potensi leakage secara eksplisit."])
    doc.add_heading("Rencana Semester Ringkas", level=1)
    rows = []
    for m in modules:
        if m["n"] in (8, 16):
            flow = "Evaluasi 100 menit"
        elif m["n"] == 15:
            flow = "Presentasi dan peer review"
        else:
            flow = "10 pembuka; 25 konsep; 45 praktik; 15 diskusi; 5 exit ticket"
        rows.append((m["n"], m["title"], flow, m["next"]))
    add_table(doc, ["Ke", "Topik", "Alur 100 menit", "Penghubung"], rows, [1.2, 5.6, 4.2, 6.1], 7.7)

    doc.add_page_break()
    doc.add_heading("Skenario Mengajar per Pertemuan", level=1)
    for m in modules:
        doc.add_heading(f"Pertemuan {m['n']}  {m['title']}", level=2)
        if m["n"] not in (8, 15, 16):
            add_table(doc, ["Menit", "Peran dosen", "Peran mahasiswa", "Bukti formatif"], [
                ("0-10", "Aktivasi pengetahuan awal dan kaitkan artefak sebelumnya", "Menjawab prompt pembuka", "Respons cepat"),
                ("10-35", "Jelaskan konsep dengan contoh dan kontra-contoh", "Mencatat keputusan penting", "Pertanyaan diagnosis"),
                ("35-80", "Demo singkat, lalu fasilitasi praktik", "Mengerjakan aktivitas terbimbing", "Notebook/tabel/grafik"),
                ("80-95", "Bahas dua hasil berbeda dan kesalahan umum", "Membandingkan dan merevisi", "Alasan revisi"),
                ("95-100", "Berikan exit ticket", "Menulis satu temuan dan satu pertanyaan", "Exit ticket"),
            ], [1.8, 5.2, 5.0, 4.2], 8)
            add_para(doc, "Tekankan: " + m["link"])
        elif m["n"] == 8:
            add_para(doc, "Kelola UTS 100 menit. Verifikasi identitas, bagikan soal, ingatkan aturan ringkasan A4, beri penanda waktu pada menit 40 dan 85, lalu kumpulkan seluruh jawaban sebelum diskusi apa pun.")
        elif m["n"] == 15:
            add_para(doc, "Gunakan slot 16 menit per kelompok: 8 menit presentasi, 3 menit demo, 5 menit tanya jawab. Pilih minimal satu pertanyaan validitas, satu pertanyaan etika, dan satu pertanyaan kontribusi teknis.")
        else:
            add_para(doc, "Periksa kelengkapan artefak, jalankan pemeriksaan reproduktibilitas sampel, lalu lakukan viva individual. Jangan memberi pertanyaan yang identik kepada semua anggota kelompok; pilih berdasarkan bagian proyek, tetapi tetap uji pemahaman pipeline keseluruhan.")
        common = {
            1: "Mahasiswa menyebut algoritma sebelum merumuskan target. Kembalikan ke keputusan dan data.",
            2: "Mahasiswa menyamakan skor train dengan kemampuan generalisasi.",
            3: "Scaler atau imputasi di-fit sebelum split; gunakan contoh kebocoran yang konkret.",
            4: "Koefisien ditafsirkan sebagai sebab-akibat; bedakan prediksi dan inferensi kausal.",
            5: "R² dianggap persentase akurasi; minta interpretasi terhadap baseline mean.",
            6: "Akurasi dipakai pada kelas timpang dan KNN tanpa scaling.",
            7: "Cluster dianggap kebenaran alami; tuntut profiling dan validasi konteks.",
            8: "Nilai alasan dan prosedur, bukan hanya angka akhir.",
            9: "PCA di-fit pada seluruh data atau PC1 dianggap fitur paling penting bagi target.",
            10: "Data berkelompok atau waktu diperlakukan sebagai sampel IID biasa.",
            11: "Orientasi confusion matrix tertukar atau threshold 0,5 dianggap wajib.",
            12: "Test set dipakai memilih hyperparameter.",
            13: "Jaringan saraf dipilih hanya karena lebih kompleks.",
            14: "Notebook tidak dapat dijalankan ulang atau klaim tidak terhubung ke bukti.",
            15: "Presentasi berisi daftar kode tanpa keputusan dan keterbatasan.",
            16: "Kontribusi kelompok menutupi lemahnya penguasaan individual.",
        }
        add_para(doc, "Kesalahan umum: " + common[m["n"]])

    doc.add_page_break()
    doc.add_heading("Kunci Latihan Ringkas", level=1)
    keys = {
        1: ["Regresi; clustering; klasifikasi.", "Karena pemilihan berdasarkan test membuat estimasi akhir optimistis.", "Contoh: fitur dibuat dari masa depan; preprocessing di-fit pada seluruh data.", "Parameter dipelajari model; hyperparameter ditetapkan sebelum fit.", "Apa keputusan yang akan dibuat dan apa biaya kesalahannya?"],
        2: ["Loss dioptimalkan saat training; metric dipakai menilai tujuan.", "Ia hanya mengukur kecocokan pada sampel yang sudah dilihat.", "Tidak; struktur dapat muncul karena skala, noise, atau pilihan algoritma.", "Contoh tingkat pendidikan; gunakan urutan bermakna bila sesuai.", "Overfitting/variance tinggi atau distribution shift."],
        3: ["Statistik test bocor ke train.", "Distribusi miring atau ada pencilan.", "Sampel serupa dapat tersebar dan membuat skor optimistis.", "Menjaga proporsi kelas.", "Impute+scale numerik, one-hot kota, target tidak ditransformasi sebagai fitur."],
        4: ["Kenaikan satu unit umur mengubah prediksi 1,8 unit dengan fitur lain tetap.", "Koefisien menjadi tidak stabil.", "Aktual lebih tinggi dari prediksi.", "Target positif dan miring, efek multiplikatif.", "Untuk membuktikan nilai tambah model."],
        5: ["Ada sedikit kesalahan ekstrem.", "Skala kesalahan bisa tetap tidak berguna dan ada bias segmen.", "MAE untuk interpretasi; RMSE bila keterlambatan ekstrem sangat mahal.", "Lebih buruk dari prediktor mean.", "Bandingkan dengan CV/split yang sama."],
        6: ["Tidak membangun model parametrik; menyimpan data.", "Jarak didominasi pendapatan.", "Fitur campuran, kebutuhan aturan yang dapat dijelaskan, atau prediksi cepat.", "Membatasi kompleksitas.", "Prediksi semua negatif sudah 98%."],
        7: ["Inertia selalu turun saat k naik.", "Centroid tertarik dan cluster berubah.", "Tidak; hasil bergantung representasi dan asumsi.", "Titik berada di batas antarcluster.", "Bentuk cluster tidak bulat atau ada noise."],
        9: ["Fitur berskala besar mendominasi varians.", "Tidak; PC1 memaksimalkan variasi X, bukan asosiasi dengan y.", "Komponen terpilih menangkap 70% variasi X.", "Komponen adalah campuran banyak fitur.", "Setelah scaler dan sebelum estimator, seluruhnya dalam pipeline."],
        10: ["Akan menggunakan masa depan untuk memprediksi masa lalu.", "Mencegah grup yang sama melintasi fold.", "Kinerja tidak stabil; perlu periksa heterogenitas fold.", "Transformasi harus di-fit hanya pada train fold.", "Data sangat besar atau biaya fit tinggi."],
        11: ["Saat false negative sangat mahal.", "40/(40+10)=0,80.", "Untuk mengendalikan false positive pada kelas negatif.", "Prevalensi kelas positif.", "Diskriminasi memeringkat; kalibrasi mencocokkan probabilitas."],
        12: ["60 fit, ditambah refit bila diaktifkan.", "Terjadi selection bias terhadap test.", "Ruang besar dan budget terbatas.", "Kombinasi terbaik menurut CV dan scoring.", "Kompleksitas hanya layak jika memberi nilai tambah."],
        13: ["Agar komposisi lapisan dapat membentuk batas non-linear.", "Training berosilasi atau divergen.", "Batch adalah subset per pembaruan; epoch satu lintasan data.", "Berhenti ketika validasi tidak membaik.", "Biaya, data, interpretabilitas, dan baseline dapat lebih unggul."],
        14: ["Seed, versi, data/sumber, pipeline, petunjuk, dan notebook berjalan ulang.", "Hasil tidak reproducible.", "Tujuan, data, metrik, penggunaan, risiko, batas.", "Laporkan apa adanya dan analisis penyebab.", "Baseline, CV/test, analisis error."],
        15: ["Jawaban dinilai dari kejelasan masalah, bukti, dan keterbatasan."] * 5,
    }
    for n, ans in keys.items():
        doc.add_heading(f"Pertemuan {n}", level=2)
        add_numbered(doc, ans)

    doc.add_page_break()
    doc.add_heading("Kunci dan Pedoman Penskoran UTS", level=1)
    add_para(doc, "Total 100 poin. Konversi ke komponen nilai akhir: skor UTS × 25%. Berikan kredit parsial untuk asumsi eksplisit dan prosedur yang benar walau angka akhir keliru.")
    add_heading = doc.add_heading
    add_heading("Bagian A 40 poin", level=2)
    add_table(doc, ["No", "Pokok jawaban", "Poin"], [
        ("1", "Parameter dipelajari; hyperparameter ditetapkan/dipilih; metrik menilai. Contoh threshold/split tree, max_depth, F1.", "8"),
        ("2", "Mean/SD seluruh data bocor. Split dulu; scaler fit pada train dan transform test melalui pipeline.", "8"),
        ("3", "Gap besar: overfit, shift, leakage terbalik, outlier. Tindakan: validasi, regularisasi/sederhanakan, audit split/data.", "8"),
        ("4", "Accuracy baseline 99%. Pilih recall/precision/F1/PR-AUC sesuai biaya.", "8"),
        ("5", "KNN perlu scaling, prediksi mahal, lokal; tree tidak perlu scaling, prediksi cepat, aturan lebih mudah dibaca.", "8"),
    ], [1.3, 13.7, 2.0])
    add_heading("Bagian B 60 poin", level=2)
    add_table(doc, ["Aspek", "Jawaban minimum", "Poin"], [
        ("Formulasi", "Satu baris per mahasiswa pada horizon prediksi; target tepat waktu; pengguna dan biaya FN/FP jelas.", "10"),
        ("Split", "Group split berdasarkan ID mahasiswa; bila prediksi kohort baru, pertimbangkan waktu.", "10"),
        ("Preprocessing", "Imputasi numerik/kategorikal, scaling untuk KNN, one-hot, semuanya dalam pipeline.", "10"),
        ("Model", "Dummy baseline plus KNN dan tree; alasan terkait data, interpretasi, dan scaling.", "10"),
        ("Evaluasi", "Recall/precision/F1 atau PR-AUC; confusion matrix; analisis per kelompok dengan ukuran sampel.", "12"),
        ("Etika", "Bias historis, privasi/stigma, human oversight, bukan dasar tunggal keputusan.", "8"),
    ], [3.2, 11.8, 2.0])

    doc.add_page_break()
    doc.add_heading("Administrasi dan Rubrik UAS", level=1)
    add_para(doc, "Gunakan rubrik 40 poin pada Modul Mahasiswa. Nilai kelompok maksimum berasal dari artefak dan presentasi; tiga poin viva bersifat individual. Jika mahasiswa tidak dapat menjelaskan bagian yang diklaim sebagai kontribusinya, dosen dapat menurunkan komponen reproduktibilitas atau penguasaan individual dengan catatan bukti.")
    add_heading("Checklist Pemeriksaan Reproduksibilitas", level=2)
    add_bullets(doc, ["README menyebut sumber dan versi dataset.", "Notebook berjalan dari kernel bersih.", "Tidak ada path lokal pribadi yang wajib.", "Random seed ditetapkan.", "Split terjadi sebelum transformasi yang belajar dari data.", "Train/validation/test dan metrik diberi label jelas.", "Gambar dan tabel dapat dihasilkan ulang.", "Sumber, lisensi, dan keterbatasan tercantum."])
    add_heading("Bank Pertanyaan Viva", level=2)
    viva = [
        "Tunjukkan unit analisis dan satu potensi duplikasi.", "Apa konsekuensi jika split diubah?", "Di mana statistik imputasi dipelajari?", "Mengapa baseline ini tepat?", "Apa hyperparameter paling berpengaruh dan mengapa?", "Mengapa metrik utama cocok dengan keputusan?", "Tunjukkan satu false positive dan jelaskan dampaknya.", "Apa bukti model tidak overfit?", "Apa arti variasi antar-fold?", "Mengapa test set hanya dipakai sekali?", "Apa kelompok yang kurang terwakili?", "Apa batas penggunaan model?", "Apa eksperimen yang gagal dan apa yang dipelajari?", "Jika latency menjadi batas, apa yang diubah?", "Bagaimana mendeteksi data drift?", "Apa perbedaan kontribusi Anda dan anggota lain?"
    ]
    add_numbered(doc, viva)
    doc.add_page_break()
    add_heading("Form Umpan Balik Kelompok", level=2)
    add_table(doc, ["Aspek", "Temuan", "Bukti", "Tindakan berikutnya"], [("Kekuatan utama", "", "", ""), ("Risiko validitas", "", "", ""), ("Risiko etika", "", "", ""), ("Perbaikan teknis", "", "", ""), ("Perbaikan komunikasi", "", "", "")], [3.2, 4.6, 4.2, 5.0])

    doc.add_page_break()
    doc.add_heading("Rekap Nilai", level=1)
    add_table(doc, ["Komponen", "Bobot", "Skor 0-100", "Nilai terbobot"], [
        ("TT1", "5%", "", ""), ("TT2", "5%", "", ""), ("TT3", "5%", "", ""), ("TT4", "5%", "", ""), ("TT5", "5%", "", ""),
        ("Kehadiran dan partisipasi", "10%", "", ""), ("UTS", "25%", "", ""), ("UAS", "40%", "", ""), ("Total", "100%", "", ""),
    ], [7.8, 2.5, 3.5, 3.8])
    add_para(doc, "Rentang nilai mengikuti RPS: A 95-100; A- 90-94; B+ 85-89; B 80-84; B- 75-79; C+ 70-74; C 65-69; C- 60-64; di bawah 60 tidak lulus.")
    path = OUT / "Panduan_Dosen_dan_Kunci_Pembelajaran_Mesin_2026-2027.docx"
    doc.save(path)
    return path


if __name__ == "__main__":
    print(build_student_module())
    print(build_teacher_guide())
