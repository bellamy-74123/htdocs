-- مخطط قاعدة بيانات نظام إدارة الصيدلية والمخزون الطبي (MySQL / MariaDB)
CREATE DATABASE IF NOT EXISTS pharmacy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pharmacy_db;

-- 1. جدول المستخدمين والصلاحيات
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'supervisor', 'staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. جدول الشركات الموردة المعتمدة
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. جدول الأدوية والمخزون الحرج
CREATE TABLE IF NOT EXISTS medicines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    generic_name VARCHAR(150),
    category VARCHAR(80) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    min_stock_alert INT NOT NULL DEFAULT 20,
    supplier_id INT,
    expiry_date DATE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
    INDEX idx_medicine_name (name),
    INDEX idx_generic_name (generic_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. جدول الطلبيات وفواتير الصرف
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_type ENUM('routine_restock', 'emergency_order', 'department_dispense') NOT NULL DEFAULT 'routine_restock',
    total_amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    status ENUM('pending', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid', 'refunded') NOT NULL DEFAULT 'paid',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. جدول تفاصيل عناصر الفاتورة
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. جدول السجل التاريخي للصرف والمبيعات لتدريب نماذج الذكاء الاصطناعي (Sales History for AI Training)
CREATE TABLE IF NOT EXISTS sales_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_id INT NOT NULL,
    quantity_sold INT NOT NULL,
    sale_date DATE NOT NULL,
    day_of_week VARCHAR(15),
    month_num INT,
    season VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE,
    INDEX idx_med_date (medicine_id, sale_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================================
-- البيانات الأولية للأدوية ثنائية اللغة (عربي وإنجليزي)
-- ==========================================================

INSERT INTO suppliers (id, name, contact_person, phone, email, address) VALUES
(1, 'الشركة الوطنية للتموين الطبي / National Medical Supply', 'أ. خالد النجار', '01-445566', 'supply@national-med.com', 'صنعاء - شارع الزبيري'),
(2, 'مؤسسة الأدوية والمستلزمات الحديثة / Modern Pharma', 'د. سامي الحداد', '01-223344', 'info@modern-pharma.com', 'صنعاء - شارع حدة'),
(3, 'مستودع الأمل للأدوية والمضادات / Al-Amal Pharma', 'أ. طارق العريقي', '01-778899', 'sales@alamal-pharma.com', 'صنعاء - الحصبة')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO users (id, username, email, password_hash, role) VALUES
(1, 'مدير الصيدلية', 'manager@hospital.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor'),
(2, 'الصيدلي (كاشير)', 'pharmacist@hospital.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff')
ON DUPLICATE KEY UPDATE username=VALUES(username);

INSERT INTO medicines (id, name, generic_name, category, price, stock_quantity, min_stock_alert, supplier_id, expiry_date) VALUES
(1, 'أوجمنتين 1 جم (Augmentin 1g)', 'أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)', 'مضاد حيوي (Antibiotic)', 45.00, 85, 25, 1, '2026-11-30'),
(2, 'كلافوكس 1 جم (Clavox 1g)', 'أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)', 'مضاد حيوي (Antibiotic)', 32.00, 40, 20, 2, '2026-10-15'),
(3, 'ميجاموكس 1 جم (Megamox 1g)', 'أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)', 'مضاد حيوي (Antibiotic)', 28.00, 60, 20, 3, '2026-12-10'),
(4, 'كيورام 1 جم (Curam 1g)', 'أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)', 'مضاد حيوي (Antibiotic)', 30.00, 55, 15, 1, '2027-03-20'),
(5, 'جلمنتين 2X 1 جم (Julmentin 2X 1g)', 'أموكسيسيلين + كلافولانات (Amoxicillin + Clavulanate)', 'مضاد حيوي (Antibiotic)', 26.50, 75, 20, 2, '2027-01-15'),
(6, 'أموكسيل 500 مجم (Amoxil 500mg)', 'أموكسيسيلين (Amoxicillin)', 'مضاد حيوي (Antibiotic)', 18.00, 120, 30, 1, '2027-02-28'),
(7, 'هيموكس 500 مجم (H-Mox 500mg)', 'أموكسيسيلين (Amoxicillin)', 'مضاد حيوي (Antibiotic)', 12.00, 95, 25, 3, '2026-12-31'),
(8, 'أموكسيدار 500 مجم (Amoxydar 500mg)', 'أموكسيسيلين (Amoxicillin)', 'مضاد حيوي (Antibiotic)', 14.50, 110, 20, 2, '2027-04-10'),
(9, 'زيثروماكس 500 مجم (Zithromax 500mg)', 'أزيثرومايسين (Azithromycin)', 'مضاد حيوي (Antibiotic)', 55.00, 35, 15, 1, '2026-11-20'),
(10, 'أزوميسين 500 مجم (Azomycin 500mg)', 'أزيثرومايسين (Azithromycin)', 'مضاد حيوي (Antibiotic)', 28.00, 70, 20, 2, '2027-05-15'),
(11, 'زيسروسين 500 مجم (Zisrocin 500mg)', 'أزيثرومايسين (Azithromycin)', 'مضاد حيوي (Antibiotic)', 22.00, 80, 20, 3, '2027-06-30'),
(12, 'سيبروباي 500 مجم (Ciprobay 500mg)', 'سيبروفلوكساسين (Ciprofloxacin)', 'مضاد حيوي (Antibiotic)', 42.00, 45, 15, 1, '2026-10-30'),
(13, 'سيبرودار 500 مجم (Ciprodar 500mg)', 'سيبروفلوكساسين (Ciprofloxacin)', 'مضاد حيوي (Antibiotic)', 19.00, 90, 25, 2, '2027-07-20'),
(14, 'تافانيك 500 مجم (Tavanic 500mg)', 'ليفوفلوكساسين (Levofloxacin)', 'مضاد حيوي (Antibiotic)', 68.00, 25, 10, 1, '2027-08-15'),
(15, 'ليفودار 500 مجم (Levodar 500mg)', 'ليفوفلوكساسين (Levofloxacin)', 'مضاد حيوي (Antibiotic)', 34.00, 50, 15, 3, '2027-09-10'),
(16, 'سيفودوكس 200 مجم (Cefodox 200mg)', 'سيفبودوكسيم (Cefpodoxime)', 'مضاد حيوي (Antibiotic)', 48.00, 38, 12, 2, '2026-12-15'),
(17, 'كلاسيد 500 مجم (Klacid 500mg)', 'كلاريثرومايسين (Clarithromycin)', 'مضاد حيوي (Antibiotic)', 62.00, 30, 10, 1, '2027-03-30'),
(18, 'فلاجيل 500 مجم (Flagyl 500mg)', 'ميترونيدازول (Metronidazole)', 'مضاد حيوي ومطهر معوي (Antibiotic & Antiprotozoal)', 11.00, 160, 30, 2, '2027-10-25'),
(19, 'بنادول إكسترا 500 مجم (Panadol Extra 500mg)', 'باراسيتامول + كافيين (Paracetamol + Caffeine)', 'مسكن وخافض حرارة (Analgesic & Antipyretic)', 12.00, 250, 50, 1, '2027-06-15'),
(20, 'بنادول أدفانس 500 مجم (Panadol Advance 500mg)', 'باراسيتامول (Paracetamol)', 'مسكن وخافض حرارة (Analgesic & Antipyretic)', 9.50, 210, 40, 1, '2027-08-10'),
(21, 'أدول 500 مجم (Adol 500mg)', 'باراسيتامول (Paracetamol)', 'مسكن وخافض حرارة (Analgesic & Antipyretic)', 7.50, 190, 35, 2, '2026-09-30'),
(22, 'فيفادول 500 مجم (Fevadol 500mg)', 'باراسيتامول (Paracetamol)', 'مسكن وخافض حرارة (Analgesic & Antipyretic)', 6.00, 240, 45, 3, '2027-11-20'),
(23, 'باراسيتامول فارما 500 مجم (Paracetamol Pharma 500mg)', 'باراسيتامول (Paracetamol)', 'مسكن وخافض حرارة (Analgesic & Antipyretic)', 5.00, 180, 30, 3, '2027-08-20'),
(24, 'ريفانين 500 مجم (Revanin 500mg)', 'باراسيتامول (Paracetamol)', 'مسكن وخافض حرارة (Analgesic & Antipyretic)', 5.50, 150, 25, 2, '2027-04-15'),
(25, 'بروفين 400 مجم (Brufen 400mg)', 'إيبوبروفين (Ibuprofen)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 15.00, 130, 25, 1, '2027-01-10'),
(26, 'بروفين 600 مجم (Brufen 600mg)', 'إيبوبروفين (Ibuprofen)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 19.50, 85, 20, 1, '2027-02-15'),
(27, 'سابوفين 400 مجم (Sapofen 400mg)', 'إيبوبروفين (Ibuprofen)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 11.00, 140, 30, 2, '2026-11-25'),
(28, 'إيبوفيل 400 مجم (Ibupophil 400mg)', 'إيبوبروفين (Ibuprofen)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 9.00, 105, 20, 3, '2027-05-30'),
(29, 'فولتارين 50 مجم (Voltaren 50mg)', 'ديكلوفيناك الصوديوم (Diclofenac Sodium)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 22.00, 75, 20, 1, '2026-08-30'),
(30, 'ديكلوجين 50 مجم (Diclogen 50mg)', 'ديكلوفيناك الصوديوم (Diclofenac Sodium)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 11.00, 95, 20, 2, '2026-12-05'),
(31, 'ديفيدو 75 مجم كبسول (Divido 75mg)', 'ديكلوفيناك الصوديوم (Diclofenac Sodium)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 28.00, 50, 15, 3, '2027-07-15'),
(32, 'كتافلام 50 مجم (Cataflam 50mg)', 'ديكلوفيناك البوتاسيوم (Diclofenac Potassium)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 24.00, 80, 20, 1, '2027-03-25'),
(33, 'رابيدوس 50 مجم (Rapidus 50mg)', 'ديكلوفيناك البوتاسيوم (Diclofenac Potassium)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 16.50, 90, 20, 2, '2027-01-30'),
(34, 'أولفين 50 مجم (Olfen 50mg)', 'ديكلوفيناك البوتاسيوم (Diclofenac Potassium)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 14.00, 65, 15, 3, '2026-10-20'),
(35, 'سيلبركس 200 مجم (Celebrex 200mg)', 'سيليكوكسيب (Celecoxib)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 65.00, 40, 10, 1, '2027-09-15'),
(36, 'سيلكوكس 200 مجم (Celcox 200mg)', 'سيليكوكسيب (Celecoxib)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 32.00, 60, 15, 2, '2027-10-30'),
(37, 'موبيك 15 مجم (Mobic 15mg)', 'ميلوكسيكام (Meloxicam)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 38.00, 45, 15, 1, '2027-06-20'),
(38, 'بونستان فورت 500 مجم (Ponstan Forte 500mg)', 'حمض الميفيناميك (Mefenamic Acid)', 'مسكن ومضاد للالتهاب (NSAID / Anti-inflammatory)', 18.00, 85, 20, 2, '2027-04-30'),
(39, 'سباسموبان 10 مجم (Spasmopan 10mg)', 'هيوسين بوتيل بروميد (Hyoscine Butylbromide)', 'مسكن لتقلصات الجهاز الهضمي (Antispasmodic)', 13.50, 110, 25, 3, '2027-08-15'),
(40, 'أوميبرازول 20 مجم (Omeprazole 20mg)', 'أوميبرازول (Omeprazole)', 'أدوية الجهاز الهضمي والمعدة (Gastrointestinal / PPI)', 20.00, 140, 30, 1, '2027-04-15'),
(41, 'نيكسيوم 40 مجم (Nexium 40mg)', 'إيزوميبرازول (Esomeprazole)', 'أدوية الجهاز الهضمي والمعدة (Gastrointestinal / PPI)', 72.00, 55, 15, 2, '2027-11-30'),
(42, 'كونترولوك 40 مجم (Controloc 40mg)', 'بانتوبرازول (Pantoprazole)', 'أدوية الجهاز الهضمي والمعدة (Gastrointestinal / PPI)', 54.00, 65, 15, 3, '2027-07-25')
ON DUPLICATE KEY UPDATE 
    name=VALUES(name),
    generic_name=VALUES(generic_name),
    category=VALUES(category),
    price=VALUES(price), 
    stock_quantity=VALUES(stock_quantity), 
    expiry_date=VALUES(expiry_date);
