# -*- coding: utf-8 -*-
"""
مولد بيانات مبيعات واستهلاك الأدوية التاريخية (Pharmacy Sales Dataset Generator)
يقوم بتوليد سجلات صرف يومية واقعية ومبنية على خصائص سريرية وزمنية حقيقية
لتدريب واختبار نماذج التعلم الآلي والتنبؤ بالطلب.
"""

import csv
import os
import random
import math
from datetime import datetime, timedelta

# قائمة الأدوية التمثيلية عبر مختلف المجموعات العلاجية
MEDICINES = [
    # 1. أدوية الجهاز الهضمي
    {"id": 1, "name": "أوميبرازول 20 مجم (Omeprazole)", "category": "أدوية الجهاز الهضمي", "price": 20.0, "base_rate": 4.5},
    {"id": 2, "name": "نيكسيوم 40 مجم (Nexium)", "category": "أدوية الجهاز الهضمي", "price": 72.0, "base_rate": 3.8},
    {"id": 3, "name": "بسكوبان 10 مجم (Buscopan)", "category": "أدوية الجهاز الهضمي", "price": 13.5, "base_rate": 5.2},
    {"id": 4, "name": "دوسباتالين 135 مجم (Duspatalin)", "category": "أدوية الجهاز الهضمي", "price": 35.0, "base_rate": 3.5},
    {"id": 5, "name": "ديفلاتيل 40 مجم (Disflatyl)", "category": "أدوية الجهاز الهضمي", "price": 12.0, "base_rate": 4.0},

    # 2. مسكنات ومضادات التهاب
    {"id": 6, "name": "بنادول إكسترا 500 مجم (Panadol Extra)", "category": "مسكنات وخافضات حرارة", "price": 12.0, "base_rate": 12.5},
    {"id": 7, "name": "فيفادول 500 مجم (Fevadol)", "category": "مسكنات وخافضات حرارة", "price": 6.0, "base_rate": 10.0},
    {"id": 8, "name": "بروفين 400 مجم (Brufen)", "category": "مضادات التهاب ومسكنات", "price": 15.0, "base_rate": 6.5},
    {"id": 9, "name": "فولتارين 50 مجم (Voltaren)", "category": "مضادات التهاب ومسكنات", "price": 22.0, "base_rate": 5.0},
    {"id": 10, "name": "كتافلام 50 مجم (Cataflam)", "category": "مضادات التهاب ومسكنات", "price": 24.0, "base_rate": 7.0},
    {"id": 11, "name": "سيلبركس 200 مجم (Celebrex)", "category": "مضادات التهاب ومسكنات", "price": 65.0, "base_rate": 3.0},

    # 3. مضادات حيوية
    {"id": 12, "name": "أوجمنتين 1 جم (Augmentin)", "category": "مضادات حيوية", "price": 45.0, "base_rate": 8.0},
    {"id": 13, "name": "أموكسيل 500 مجم (Amoxil)", "category": "مضادات حيوية", "price": 18.0, "base_rate": 7.5},
    {"id": 14, "name": "زيثروماكس 500 مجم (Zithromax)", "category": "مضادات حيوية", "price": 55.0, "base_rate": 4.5},
    {"id": 15, "name": "سيبروباي 500 مجم (Ciprobay)", "category": "مضادات حيوية", "price": 42.0, "base_rate": 4.0},
    {"id": 16, "name": "فلاجيل 500 مجم (Flagyl)", "category": "مضادات حيوية ومطهرات معوية", "price": 11.0, "base_rate": 6.0},

    # 4. أمراض القلب والضغط والكوليسترول
    {"id": 17, "name": "كونكور 5 مجم (Concor)", "category": "أدوية القلب والضغط", "price": 38.0, "base_rate": 7.0},
    {"id": 18, "name": "لوسارتان 50 مجم (Cozaar)", "category": "أدوية القلب والضغط", "price": 32.0, "base_rate": 5.5},
    {"id": 19, "name": "أملوديبين 5 مجم (Norvasc)", "category": "أدوية القلب والضغط", "price": 28.0, "base_rate": 4.8},
    {"id": 20, "name": "ليبيتور 20 مجم (Lipitor)", "category": "أدوية خفض الكوليسترول", "price": 55.0, "base_rate": 8.5},
    {"id": 21, "name": "كريستور 10 مجم (Crestor)", "category": "أدوية خفض الكوليسترول", "price": 68.0, "base_rate": 6.0},
    {"id": 22, "name": "بلافيكس 75 مجم (Plavix)", "category": "أدوية السيولة والجلطات", "price": 85.0, "base_rate": 5.0},
    {"id": 23, "name": "أسبرين بروتكت 81 مجم (Aspirin Protect)", "category": "أدوية السيولة والجلطات", "price": 14.0, "base_rate": 9.0},

    # 5. أدوية السكري والغدد
    {"id": 24, "name": "جلوكوفاج 500 مجم (Glucophage)", "category": "أدوية السكري", "price": 16.0, "base_rate": 11.0},
    {"id": 25, "name": "دياميكرون 60 مجم (Diamicron MR)", "category": "أدوية السكري", "price": 28.0, "base_rate": 5.0},
    {"id": 26, "name": "جارديانس 10 مجم (Jardiance)", "category": "أدوية السكري", "price": 95.0, "base_rate": 4.2},
    {"id": 27, "name": "جانيوفيا 100 مجم (Januvia)", "category": "أدوية السكري", "price": 110.0, "base_rate": 3.8},
    {"id": 28, "name": "إنسولين لانتوس (Lantus SoloStar)", "category": "أدوية السكري", "price": 120.0, "base_rate": 3.5},
    {"id": 29, "name": "يوثيروكس 50 ميكروجرام (Euthyrox)", "category": "أدوية الغدة الدرقية", "price": 18.0, "base_rate": 6.5},

    # 6. أدوية الجهاز التنفسي والحساسية
    {"id": 30, "name": "فنتولين بخاخ (Ventolin Inhaler)", "category": "أدوية الجهاز التنفسي", "price": 22.0, "base_rate": 7.5},
    {"id": 31, "name": "سيمبيكورت 160 (Symbicort Turbuhaler)", "category": "أدوية الجهاز التنفسي", "price": 98.0, "base_rate": 3.2},
    {"id": 32, "name": "سيتريزين 10 مجم (Zyrtec)", "category": "أدوية الحساسية", "price": 16.5, "base_rate": 6.0},
    {"id": 33, "name": "تيلفاست 120 مجم (Telfast)", "category": "أدوية الحساسية", "price": 34.0, "base_rate": 4.5},
    {"id": 34, "name": "أوتريفين بخاخ أنف (Otrivin)", "category": "أدوية الحساسية والأنف", "price": 14.0, "base_rate": 5.5},

    # 7. فيتامينات ومعادن ومكملات
    {"id": 35, "name": "فيدروب نقط فيتامين د (Vidrop)", "category": "فيتامينات ومكملات", "price": 12.0, "base_rate": 7.0},
    {"id": 36, "name": "نيوروبيون ب12 (Neurobion)", "category": "فيتامينات ومكملات", "price": 25.0, "base_rate": 6.8},
    {"id": 37, "name": "فيروجلوبين حديد (Feroglobin)", "category": "فيتامينات ومكملات", "price": 32.0, "base_rate": 5.2},
    {"id": 38, "name": "كالترات كالسيوم (Caltrate)", "category": "فيتامينات ومكملات", "price": 42.0, "base_rate": 4.0},
    {"id": 39, "name": "فيتامين سي 1000 مجم فوار (Vitamin C)", "category": "فيتامينات ومكملات", "price": 18.0, "base_rate": 8.0},
    {"id": 40, "name": "بروبيوتيك لاكتوباسيلوس (Probiotic)", "category": "فيتامينات ومكملات", "price": 55.0, "base_rate": 3.5}
]

def generate_sales_dataset(output_path: str, days: int = 365):
    """توليد سجل يومي للصرف لكل دواء مع حساب الخصائص المتقدمة"""
    random.seed(42)
    start_date = datetime.now() - timedelta(days=days)

    fieldnames = [
        "date", "medicine_id", "medicine_name", "category", "unit_price",
        "day_of_week", "is_weekend", "month", "season",
        "lag_1_demand", "lag_7_demand", "rolling_mean_7",
        "stockout_flag", "daily_demand"
    ]

    total_rows = 0
    with open(output_path, "w", newline="", encoding="utf-8-sig") as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()

        for med in MEDICINES:
            history = []
            base = med["base_rate"]
            cat = med["category"]

            for day_idx in range(days):
                curr_date = start_date + timedelta(days=day_idx)
                dow = curr_date.weekday() # 0 = Monday, 6 = Sunday
                is_weekend = 1 if dow in [4, 5] else 0 # الجمعة والسبت
                month = curr_date.month

                # تحديد الموسم
                if month in [12, 1, 2]:
                    season = "Winter"
                elif month in [3, 4, 5]:
                    season = "Spring"
                elif month in [6, 7, 8]:
                    season = "Summer"
                else:
                    season = "Autumn"

                # عامل الموسمية للمضادات وأدوية التنفس والمسكنات
                seasonal_multiplier = 1.0
                if "مضادات" in cat or "تنفسي" in cat or "مسكن" in cat or "حساسية" in cat:
                    if season in ["Winter", "Autumn"]:
                        seasonal_multiplier = 1.35 # زيادة في موسم البرد
                    else:
                        seasonal_multiplier = 0.85
                elif "هضمي" in cat and season == "Summer":
                    seasonal_multiplier = 1.20 # زيادة النزلات في الصيف

                # عامل أيام الأسبوع (نشاط أعلى وسط الأسبوع)
                day_multiplier = 1.15 if dow in [0, 1, 2, 6] else 0.80

                # تأثير رواتب بداية الشهر
                payday_multiplier = 1.25 if curr_date.day in [28, 29, 30, 31, 1, 2, 3, 4] else 1.0

                # حساب التوقع النظري مع ضوضاء عشوائية غاوسية واقعية
                noise = random.gauss(0, 0.8)
                expected_demand = base * seasonal_multiplier * day_multiplier * payday_multiplier + noise
                actual_demand = max(1, int(round(expected_demand)))

                # الخصائص الزمنية المتأخرة (Lags & Rolling Mean)
                lag_1 = history[-1] if len(history) >= 1 else actual_demand
                lag_7 = history[-7] if len(history) >= 7 else (history[0] if history else actual_demand)
                last_7 = history[-7:] if len(history) >= 7 else history
                rolling_7 = round(sum(last_7) / len(last_7), 2) if last_7 else float(actual_demand)

                history.append(actual_demand)

                row = {
                    "date": curr_date.strftime("%Y-%m-%d"),
                    "medicine_id": med["id"],
                    "medicine_name": med["name"],
                    "category": med["category"],
                    "unit_price": med["price"],
                    "day_of_week": dow,
                    "is_weekend": is_weekend,
                    "month": month,
                    "season": season,
                    "lag_1_demand": lag_1,
                    "lag_7_demand": lag_7,
                    "rolling_mean_7": rolling_7,
                    "stockout_flag": 0,
                    "daily_demand": actual_demand
                }
                writer.writerow(row)
                total_rows += 1

    print(f"Dataset generated successfully with {total_rows} records at: {output_path}")
    return total_rows

if __name__ == "__main__":
    out = os.path.join(os.path.dirname(os.path.abspath(__file__)), "pharmacy_sales_history.csv")
    generate_sales_dataset(out, days=365)
