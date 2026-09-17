# -*- coding: utf-8 -*-
"""
Generate Master Software Engineering Diagrams Showcase Document (Word DOCX & PDF)
Strictly conforms to:
- Requirements from Software Engineering course in C:\\Users\\bella\\Downloads\\Software Engineering-1
- PURE BLACK & WHITE / GRAYSCALE: White boxes, white circles, crisp black borders, black text
- NO COLORS!
- All 8 Diagrams + Use Case Specification Template from قالب التوصيف.docx
"""

import os
import subprocess
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

out_dir = r"C:\Users\bella\Downloads"
img_dir = os.path.join(out_dir, "SPMS_Diagrams_BlackWhite")
docx_path = os.path.join(out_dir, "SPMS_Software_Engineering_Diagrams.docx")
pdf_path = os.path.join(out_dir, "SPMS_Software_Engineering_Diagrams.pdf")
html_path = os.path.join(out_dir, "SPMS_Software_Engineering_Diagrams.html")
chrome_path = r"C:\Program Files\Google\Chrome\Application\chrome.exe"

def build_word():
    doc = Document()

    # Configure Margins (A4, 1.0 inch)
    for section in doc.sections:
        section.top_margin = Inches(1.0)
        section.bottom_margin = Inches(1.0)
        section.left_margin = Inches(1.0)
        section.right_margin = Inches(1.0)
        
        sectPr = section._sectPr
        bidi = parse_xml(f'<w:bidi {nsdecls("w")}/>')
        sectPr.append(bidi)

        # Header
        header = section.header
        p_h = header.paragraphs[0]
        p_h._p.get_or_add_pPr().append(parse_xml(f'<w:bidi {nsdecls("w")}/>'))
        p_h.alignment = WD_ALIGN_PARAGRAPH.LEFT
        r_h = p_h.add_run("نظام إدارة الصيدلية الذكي (SPMS) — مخططات هندسة البرمجيات (Software Engineering Diagrams)")
        r_h.font.name = "Arial"
        r_h.font.size = Pt(8.5)
        r_h.font.color.rgb = RGBColor(100, 116, 139)

        # Footer
        footer = section.footer
        p_f = footer.paragraphs[0]
        p_f._p.get_or_add_pPr().append(parse_xml(f'<w:bidi {nsdecls("w")}/>'))
        p_f.alignment = WD_ALIGN_PARAGRAPH.CENTER
        r_f = p_f.add_run("تصميم هندسي أبيض وأسود قياسي — مقتبس من مقرر هندسة البرمجيات — جامعة صنعاء 2026 م")
        r_f.font.name = "Arial"
        r_f.font.size = Pt(9)
        r_f.font.italic = True
        r_f.font.color.rgb = RGBColor(100, 116, 139)

    def make_rtl_p(p):
        pPr = p._p.get_or_add_pPr()
        pPr.append(parse_xml(f'<w:bidi {nsdecls("w")}/>'))

    def make_rtl_tbl(tbl):
        tblPr = tbl._tbl.tblPr
        tblPr.append(parse_xml(f'<w:bidiVisual {nsdecls("w")}/>'))

    def set_cell_background(cell, fill_hex):
        tcPr = cell._tc.get_or_add_tcPr()
        shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
        tcPr.append(shd)

    def set_cell_margins(cell, top=70, bottom=70, left=100, right=100):
        tcPr = cell._tc.get_or_add_tcPr()
        tcMar = parse_xml(f'''
            <w:tcMar {nsdecls("w")}>
                <w:top w:w="{top}" w:type="dxa"/>
                <w:bottom w:w="{bottom}" w:type="dxa"/>
                <w:left w:w="{left}" w:type="dxa"/>
                <w:right w:w="{right}" w:type="dxa"/>
            </w:tcMar>
        ''')
        tcPr.append(tcMar)

    def set_table_borders(table):
        tblPr = table._tbl.tblPr
        borders = parse_xml(f'''
            <w:tblBorders {nsdecls("w")}>
                <w:top w:val="single" w:sz="6" w:space="0" w:color="000000"/>
                <w:bottom w:val="single" w:sz="6" w:space="0" w:color="000000"/>
                <w:left w:val="single" w:sz="6" w:space="0" w:color="000000"/>
                <w:right w:val="single" w:sz="6" w:space="0" w:color="000000"/>
                <w:insideH w:val="single" w:sz="4" w:space="0" w:color="000000"/>
                <w:insideV w:val="single" w:sz="4" w:space="0" w:color="000000"/>
            </w:tblBorders>
        ''')
        tblPr.append(borders)

    def add_r(p, text, font_name="Arial", size_pt=11.5, bold=False, italic=False, color_rgb=None):
        run = p.add_run(text)
        run.font.name = font_name
        run.font.size = Pt(size_pt)
        run.font.bold = bold
        run.font.italic = italic
        if color_rgb:
            run.font.color.rgb = color_rgb
        rPr = run._r.get_or_add_rPr()
        rPr.append(parse_xml(f'<w:rFonts {nsdecls("w")} w:ascii="{font_name}" w:hAnsi="{font_name}" w:cs="{font_name}"/>'))
        rPr.append(parse_xml(f'<w:rtl {nsdecls("w")}/>'))
        return run

    def h1_t(text):
        p = doc.add_paragraph(); make_rtl_p(p); p.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p.paragraph_format.space_before = Pt(14); p.paragraph_format.space_after = Pt(6); p.paragraph_format.keep_with_next = True
        add_r(p, text, font_name="Arial", size_pt=15, bold=True, color_rgb=RGBColor(0, 0, 0))
        return p

    def h2_t(text):
        p = doc.add_paragraph(); make_rtl_p(p); p.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p.paragraph_format.space_before = Pt(10); p.paragraph_format.space_after = Pt(4); p.paragraph_format.keep_with_next = True
        add_r(p, text, font_name="Arial", size_pt=12.5, bold=True, color_rgb=RGBColor(0, 0, 0))
        return p

    def para(text):
        p = doc.add_paragraph(); make_rtl_p(p); p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.2; p.paragraph_format.space_after = Pt(4)
        add_r(p, text, font_name="Arial", size_pt=11, color_rgb=RGBColor(0, 0, 0))
        return p

    def bullet(text):
        p = doc.add_paragraph(); make_rtl_p(p); p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.15; p.paragraph_format.space_after = Pt(3); p.paragraph_format.left_indent = Inches(0.2)
        add_r(p, "• ", font_name="Arial", size_pt=11, bold=True, color_rgb=RGBColor(0, 0, 0))
        add_r(p, text, font_name="Arial", size_pt=11, color_rgb=RGBColor(0, 0, 0))
        return p

    def add_diag_image(img_name, caption_text):
        full_img_p = os.path.join(img_dir, f"{img_name}.png")
        if os.path.exists(full_img_p):
            p_img = doc.add_paragraph()
            p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_img.paragraph_format.space_before = Pt(8)
            p_img.paragraph_format.space_after = Pt(4)
            p_img.add_run().add_picture(full_img_p, width=Inches(6.2))

            p_cap = doc.add_paragraph(); make_rtl_p(p_cap)
            p_cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_cap.paragraph_format.space_after = Pt(12)
            add_r(p_cap, caption_text, font_name="Arial", size_pt=10, bold=True, italic=True, color_rgb=RGBColor(0, 0, 0))

    # COVER PAGE
    p_cov = doc.add_paragraph(); make_rtl_p(p_cov); p_cov.alignment = WD_ALIGN_PARAGRAPH.CENTER; p_cov.paragraph_format.space_before = Pt(40)
    add_r(p_cov, "الجمهورية اليمنية — جامعة صنعاء\nكلية الحاسوب وتكنولوجيا المعلومات — قسم علوم الحاسوب\n\n", font_name="Arial", size_pt=13, bold=True)
    add_r(p_cov, "توثيق وتصميم كافة مخططات هندسة البرمجيات\n", font_name="Arial", size_pt=18, bold=True)
    add_r(p_cov, "Software Engineering Diagrams\n", font_name="Arial", size_pt=15, bold=True)
    add_r(p_cov, "نظام إدارة الصيدلية الذكي (Smart Pharmacy Management System - SPMS)\n\n", font_name="Arial", size_pt=13, bold=True)
    add_r(p_cov, "وفق المعايير القياسية لمقرر هندسة البرمجيات (مربعات ودوائر بيضاء بسيطة — بدون ألوان نهائياً)\n\n", font_name="Arial", size_pt=11, italic=True)

    # TABLE OF DIAGRAMS
    doc.add_page_break()
    h1_t("فهرس المخططات الهندسية المعتمدة (List of Software Engineering Diagrams)")
    para("تم تصميم كافة الرسومات والمخططات التالية استناداً إلى المحاضرات والمعايير القياسية لمقرر هندسة البرمجيات (Software Engineering)، وتم تطبيق أسلوب الرسم المعتمد المتمثل في: المربعات والمستطيلات البيضاء، الدوائر والأشكال البيضاوية البيضاء، الخطوط والأسهم السوداء النقية، دون استخدام أي ألوان:")
    
    bullet("شكل (3-1): مخطط السياق للنظام (Context Diagram - DFD Level 0).")
    bullet("شكل (3-2): مخطط تدفق البيانات التفصيلي (DFD Level 1 Diagram).")
    bullet("شكل (3-3): مخطط حالات الاستخدام للنظام (Use Case Diagram).")
    bullet("شكل (3-4): مخطط الكيانات والعلاقات العلائقي (ERD Diagram - 3NF).")
    bullet("شكل (3-5): مخطط الفئات كائنية التوجه (Class Diagram - OOP).")
    bullet("شكل (3-6): مخطط التتابع والتسلسل الزمني (Sequence Diagram).")
    bullet("شكل (3-7): مخطط النشاطات لعملية الصرف وفرز الصلاحيات (Activity Diagram).")
    bullet("شكل (3-8): مخطط الحالات لدورة حياة الدفعة الدوائية (State Chart Diagram).")
    bullet("جدول (3-1): قالب التوصيف القياسي لحالة الاستخدام (Use Case Specification Template).")

    # 1. CONTEXT DIAGRAM
    doc.add_page_break()
    h1_t("1. مخطط السياق للنظام (Context Diagram - DFD Level 0)")
    para("يمثل مخطط السياق (Context Diagram) أعلى مستوى في مخططات تدفق البيانات (Data Flow Diagram - DFD)، حيث يتم تمثيل النظام الصيدلاني بالكامل كعملية واحدة مركزية برقم (0) داخل دائرة بيضاء، وتحيط بها الكيانات الخارجية (External Entities) المتمثلة في مربعات/مستطيلات بيضاء تبين تدفقات البيانات الداخلة والخارجة بدقة:")
    add_diag_image("01_Context_Diagram_DFD0", "شكل (3-1): مخطط السياق للنظام (Context Diagram - DFD Level 0)")
    bullet("الدائرة المركزية (0): نظام إدارة الصيدلية الذكي (SPMS) — يمثل النظام بأكمله ومحرك الذكاء الاصطناعي.")
    bullet("الكيان الخارجي (مدير الصيدلية): يغذي النظام بإعدادات الأصناف والصلاحيات، ويستلم التقارير التحليلية وإنذارات الشذوذ.")
    bullet("الكيان الخارجي (الصيدلي المناوب): يرسل استعلامات الأدوية وطلبات الفواتير، ويستلم نتائج البحث الذكي وتوجيهات FEFO.")
    bullet("الكيان الخارجي (الموردون): يستلمون أوامر الشراء المؤتمتة عند بلوغ نقطة الطلب (ROP)، ويغذون النظام ببيانات الدفعات الواردة.")
    bullet("الكيان الخارجي (العميل / المريض): يقدم الوصفة الدوائية ويستلم فاتورة الصرف والتوصيات الإرشادية.")

    # 2. DFD LEVEL 1
    doc.add_page_break()
    h1_t("2. مخطط تدفق البيانات التفصيلي (DFD Level 1 Diagram)")
    para("يقوم مخطط المستوى الأول (DFD Level 1) بتفكيك العملية الرئيسية (0) إلى أربع عمليات تفصيلية أساسية مرقمة (1.0 إلى 4.0) وممثلة بدوائر بيضاء، مع ربطها بمخازن البيانات الأربعة (Data Stores D1 - D4) الممثلة بخطوط أفقية متوازية بيضاء وفق معايير المحاضرة الخامسة (Lect_5):")
    add_diag_image("02_DFD_Level1_Diagram", "شكل (3-2): مخطط تدفق البيانات التفصيلي (DFD Level 1 Diagram)")
    bullet("العملية (1.0) إدارة المخزون والأصناف: تتعامل مع بيانات الأدوية وتخزنها في مستودع البيانات (D1: جدول الأدوية).")
    bullet("العملية (2.0) نقطة البيع والصرف (POS): تنفذ عمليات صرف الدواء وإصدار الفواتير وتسجلها في (D3: جدول المبيعات).")
    bullet("العملية (3.0) محرك التنبؤ والشذوذ (AI Engine): تحلل المبيعات التاريخية وتنتج توقعات ARIMA لنقاط الطلب وتخزنها في (D4: سجل التنبؤات).")
    bullet("العملية (4.0) فرز الدفعات وإدارة الصلاحيات (FEFO): تفرز الدفعات تصاعدياً بحسب تاريخ الانتهاء وتحدثها في (D2: جدول الدفعات).")

    # 3. USE CASE DIAGRAM & SPECIFICATION
    doc.add_page_break()
    h1_t("3. مخطط حالات الاستخدام للنظام (Use Case Diagram)")
    para("يوضح مخطط حالات الاستخدام (Use Case Diagram) المتطلبات الوظيفية للنظام وحدوده البرمجية، حيث تُمثل حالات الاستخدام بأشكال بيضاوية بيضاء نقية (White Ovals) داخل مستطيل حدود النظام (System Boundary)، وتتفاعل مع الفواعل الثلاثة (Actors: الصيدلي، مدير الصيدلية، أمين المخزن) باستخدام علاقات الارتباط والاشتمال <<include>> والامتداد <<extend>> وفق معايير المحاضرة السادسة (Lect_6):")
    add_diag_image("03_UseCase_Diagram", "شكل (3-3): مخطط حالات الاستخدام للنظام (Use Case Diagram)")

    h2_t("قالب التوصيف القياسي لحالة الاستخدام (Use Case Specification)")
    para("فيما يلي قالب التوصيف المقتبس حرفياً وتنسيقياً من الملف الأكاديمي المرفق (قالب التوصيف.docx) لمقرر هندسة البرمجيات، مطبقاً على حالة الاستخدام المركزية (معالجة عملية البيع والصرف الذكي):")

    t_spec = doc.add_table(rows=18, cols=2); make_rtl_tbl(t_spec); set_table_borders(t_spec); t_spec.alignment = WD_TABLE_ALIGNMENT.CENTER
    spec_data = [
        ("Unique ID", "UC-03"),
        ("Use case name :", "معالجة عملية البيع والصرف وتطبيق فرز FEFO (Process Sale & FEFO Allocation)"),
        ("Area: Actors :", "الصيدلي المناوب (Pharmacist)"),
        ("Description:", "تمكين الصيدلي من البحث عن الدواء، اختيار الدفعة الأقرب انتهاءً آلياً، فحص شذوذ الكميات، توليد توصيات السلة، وإصدار الفاتورة المعتمدة."),
        ("Triggering Event:", "طلب العميل شراء أو صرف دواء بموجب وصفة طبية أو طلب مباشر."),
        ("Trigger Type", "External (طلب مباشر من العميل الصيدلاني)"),
        ("Information Of Steps (1-5)", "1. إدخال استعلام البحث عن الدواء في واجهة POS.\n2. يقوم محرك البحث الذكي (Fuzzy Matcher) بمطابقة الأسماء التجارية والعلمية.\n3. اختيار الصنف والكمية المطلوبة.\n4. يقوم النظام بتطبيق خوارزمية FEFO وفرز الدفعات تصاعدياً بحسب الأقرب انتهاءً.\n5. فحص كمية الصرف بواسطة خوارزمية Z-Score للتأكد من عدم وجود قفزة شاذة.\n6. توليد توصيات مرافقة عبر خوارزمية Apriori.\n7. تأكيد الفاتورة وخصم الكمية من الدفعة المختارة وطباعة الإيصال."),
        ("Steps Performed (main path)", "المسار الأساسي: بحث -> مطابقة -> ترشيح دفعة FEFO -> توصيات السلة -> تأكيد البيع -> طباعة الفاتورة."),
        ("Preconditions", "1. تسجيل دخول الصيدلي إلى النظام بصلاحيات صحيحة.\n2. توفر رصيد دوائي صالح في جدول الدفعات."),
        ("Post conditions", "1. خصم الكمية المصروفة من رصيد الدفعة المحددة آلياً.\n2. إنشاء سجل مبيعات جديد في جدول sales و sale_items.\n3. طباعة إيصال الفاتورة للعميل."),
        ("Assumptions:", "توفر اتصال محلي سريع بين واجهة العرض وخادم التطبيقات وقاعدة البيانات."),
        ("Requirements Met:", "أتمتة الصرف الدوائي، خفض هدر الأدوية بنسبة 30%، ومنع التلاعب المخزني."),
        ("Outstanding Issues", "دعم الدفع الإلكتروني المباشر (محافظ نقدية)."),
        ("Priority", "أولوية قصوى (High Priority - Core Function)"),
        ("Risk", "منخفض (Low Risk - مجرب ومفحوص بنسبة 100%)"),
        ("Alternative Path 1", "في حال عدم توفر رصيد للصنف: يعرض النظام آلياً البدائل الدوائية المتكافئة علاجياً."),
        ("Alternative Path 2", "في حال رصد شذوذ (Z > 2.0): يطلب النظام تأكيداً ثانياً من الصيدلي قبل الإتمام."),
        ("Business Rules", "منع صرف الدفعة منتهية الصلاحية نهائياً، والالتزام بفرز الأقرب انتهاءً أولاً (FEFO).")
    ]

    for ri, (k, v) in enumerate(spec_data):
        c0 = t_spec.cell(ri, 0); p0 = c0.paragraphs[0]; make_rtl_p(p0)
        set_cell_background(c0, "FFFFFF"); set_cell_margins(c0, top=60, bottom=60, left=80, right=80)
        add_r(p0, k, font_name="Arial", size_pt=10, bold=True, color_rgb=RGBColor(0, 0, 0))

        c1 = t_spec.cell(ri, 1); p1 = c1.paragraphs[0]; make_rtl_p(p1)
        set_cell_background(c1, "FFFFFF"); set_cell_margins(c1, top=60, bottom=60, left=80, right=80)
        add_r(p1, v, font_name="Arial", size_pt=9.5, color_rgb=RGBColor(0, 0, 0))

    # 4. CLASS DIAGRAM
    doc.add_page_break()
    h1_t("4. مخطط الفئات كائنية التوجه (Class Diagram - OOP)")
    para("يعكس مخطط الفئات المعمارية البرمجية كائنية التوجه للنظام، حيث تُمثل الفئات البرمجية بمستطيلات ومربعات بيضاء ثلاثية الأقسام (اسم الفئة، الحقول والمتغيرات، الدوال والعمليات)، مع تطبيق مبدأ المسؤولية الواحدة (Single Responsibility Principle) عبر عزل محرك الذكاء الاصطناعي في كلاس مستقل ModelPredictor:")
    add_diag_image("05_Class_Diagram", "شكل (3-5): مخطط الفئات كائنية التوجه (Class Diagram - OOP)")
    bullet("الفئة المركزية (ModelPredictor): مسؤولة عن كافة الخوارزميات الذكية (ARIMA, ROP, FEFO, Apriori, Z-Score, Fuzzy Matcher).")
    bullet("الفئة الكيانية (Medicine): تمثل الصنف الدوائي وترتبط بعلاقة (1 إلى متعدد) مع فئة الدفعات Batch.")
    bullet("الفئة اللوجستية (Batch): تمثل الدفعة وتاريخ صلاحيتها ورصيدها وترتبط ببنود الفاتورة SaleItem.")
    bullet("الفئة المعاملاتية (Sale): تمثل الفاتورة وتحتوي على قائمة بنود الصرف SaleItem.")

    # 5. SEQUENCE DIAGRAM
    doc.add_page_break()
    h1_t("5. مخطط التتابع والتسلسل الزمني (Sequence Diagram)")
    para("يوضح مخطط التتابع التفاعل الزمني لتبادل الرسائل والنداءات بين كائنات النظام أثناء عملية الصرف، وتُمثل خطوط الحياة (Lifelines) بمربعات بيضاء علوية وخطوط رأسية متقطعة وأشرطة تنفيذ مستطيلة بيضاء رفيعة (Execution Bars) وفق معايير المحاضرة السابعة (Lect_7):")
    add_diag_image("06_Sequence_Diagram", "شكل (3-6): مخطط التتابع والتسلسل الزمني (Sequence Diagram)")
    bullet("الخطوات (1-7): إدخال الاستعلام، استدعاء البحث الذكي، وحساب الملاءمة الدلالية واسترجاع النتائج في أقل من 15 مللي ثانية.")
    bullet("الخطوات (8-12): اختيار الدواء، طلب فرز الدفعات آلياً بحسب تاريخ الانتهاء، وعرض الدفعة الأقرب انتهاءً والتوصيات المرافقة.")
    bullet("الخطوات (13-18): تأكيد الشراء، حفظ حركة البيع في قاعدة البيانات، خصم الرصيد من الدفعة، وطباعة الفاتورة للعميل.")

    # 6. ACTIVITY DIAGRAM
    doc.add_page_break()
    h1_t("6. مخطط النشاطات وتدفق العمليات (Activity Diagram)")
    para("يمثل مخطط النشاطات التدفق المنطقي لعملية الصرف الصيدلاني، وتُمثل الأنشطة بمستطيلات بيضاء مستديرة الزوايا (Rounded Rectangles)، ونقاط اتخاذ القرار بمعينات بيضاء (Diamonds)، مع نقطة بداية سوداء صلبة ونقطة نهاية دائرية مزدوجة (Bullseye) وفق معايير المحاضرة الثامنة (Lect_8):")
    add_diag_image("07_Activity_Diagram", "شكل (3-7): مخطط النشاطات لعملية الصرف وفرز الصلاحيات (Activity Diagram)")
    bullet("البحث والتحقق: فحص توفر الدواء، وفي حال عدم التوفر يتم الانتقال آلياً لمسار عرض البدائل المتكافئة.")
    bullet("فرز FEFO الإلزامي: استرجاع الدفعات وتصنيفها، وتفعيل التنبيه العاجل للدفعات التي تقل صلاحيتها عن 30 يوماً.")
    bullet("الفحص الذكي: فحص الشذوذ Z-Score وتوليد توصيات السلة السريرية Apriori قبل إتمام الفاتورة وطباعتها.")

    # 7. STATE CHART DIAGRAM
    doc.add_page_break()
    h1_t("7. مخطط الحالات لدورة حياة الدفعة الدوائية (State Chart Diagram)")
    para("يوضح مخطط الحالات (State Chart Diagram) التحولات السلوكية لحالات كائن الدفعة الدوائية (Batch Object) عبر دورة حياتها داخل الصيدلية، وتُمثل الحالات بمستطيلات بيضاء مستديرة الزوايا والانتقالات بأسهم مشروطة بالحدث والشرط [Event/Condition] وفق معايير المحاضرة الثامنة (Lect_8):")
    add_diag_image("08_State_Chart_Diagram", "شكل (3-8): مخطط الحالات لدورة حياة الدفعة الدوائية (State Chart Diagram)")
    bullet("الحالة (مستلمة بالمستودع): حالة الفحص الابتدائي للشحنة ومطابقة الفاتورة.")
    bullet("الحالة (صالحة بالمخزون): الصلاحية آمنة ومتبقي أكثر من 180 يوماً.")
    bullet("الحالة (تنبيه مبكر): صلاحية بين 91 و 180 يوماً وتنبيه إدارة المشتريات.")
    bullet("الحالة (أولوية متوسطة): صلاحية بين 31 و 90 يوماً مع إدراجها بالعروض.")
    bullet("الحالة (أولوية حرجة / عاجل): صلاحية أقل من 30 يوماً وصرف إلزامي فوري في POS لمنع التلف.")
    bullet("الحالة النهائية (مصروفة بالكامل / تالفة ومحجوزة للإتلاف): نهاية دورة حياة الدفعة.")

    # 8. ERD DIAGRAM
    doc.add_page_break()
    h1_t("8. مخطط الكيانات والعلاقات (ERD Diagram - 3NF)")
    para("يمثل مخطط الكيانات والعلاقات بنية قاعدة البيانات العلائقية المصممة بالنموذج العادي الثالث (3NF)، حيث تُمثل الكيانات بمستطيلات بيضاء نقية والعلاقات بمعينات بيضاء نقية:")
    add_diag_image("04_ERD_Diagram", "شكل (3-4): مخطط الكيانات والعلاقات (ERD Diagram - 3NF)")
    bullet("جدول MEDICINES يرتبط بجدول BATCHES بعلاقة (1 إلى متعدد) لتمكين تتبع عدة دفعات وتواريخ انتهاء لكل صنف.")
    bullet("جدول SALES يرتبط بجدول SALE_ITEMS بعلاقة (1 إلى متعدد) لتوثيق بنود الفاتورة وحركات الصرف.")
    bullet("جدول BATCHES يغذي SALE_ITEMS بخصم مباشر من الدفعة المحددة وفق منطق FEFO.")

    doc.save(docx_path)
    print(f"[Word Showcase Generated]: {docx_path}")

def build_pdf():
    # Build complete HTML showcase and convert via Chrome to PDF
    img_rel_dir = "SPMS_Diagrams_BlackWhite"
    
    html_content = f"""<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>مخططات هندسة البرمجيات - نظام إدارة الصيدلية الذكي SPMS</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Amiri:wght@400;700&display=swap');
  @page {{ size: A4 portrait; margin: 15mm 15mm 15mm 15mm; }}
  body {{ font-family: 'Times New Roman', 'Amiri', serif; font-size: 11.5pt; line-height: 1.45; color: #000000; background: #FFFFFF; margin: 0; padding: 0; text-align: justify; direction: rtl; }}
  .page {{ page-break-after: always; min-height: 94vh; padding: 10px; box-sizing: border-box; }}
  .page:last-child {{ page-break-after: auto; }}
  .page-header-line {{ border-top: 2px solid #000000; margin-bottom: 12px; padding-top: 4px; font-size: 9pt; color: #333333; display: flex; justify-content: space-between; font-family: 'Cairo', sans-serif; }}
  h1 {{ font-size: 16pt; font-weight: bold; text-align: center; color: #000000; border-bottom: 2px solid #000000; padding-bottom: 4px; margin-top: 10px; margin-bottom: 12px; font-family: 'Cairo', sans-serif; }}
  h2 {{ font-size: 13pt; font-weight: bold; color: #000000; border-right: 4px solid #000000; padding-right: 8px; margin-top: 12px; margin-bottom: 6px; font-family: 'Cairo', sans-serif; }}
  p {{ margin-bottom: 6px; text-indent: 10px; }}
  ul {{ margin-top: 4px; margin-bottom: 8px; padding-right: 20px; }}
  li {{ margin-bottom: 3px; }}
  .img-box {{ text-align: center; margin: 10px 0; }}
  .img-box img {{ max-width: 95%; max-height: 520px; border: 1px solid #000000; background: #FFFFFF; }}
  .caption {{ font-size: 10pt; font-weight: bold; font-style: italic; margin-top: 4px; text-align: center; }}
  table {{ width: 100%; border-collapse: collapse; margin: 8px 0; font-size: 9.5pt; direction: rtl; }}
  table, th, td {{ border: 1px solid #000000; }}
  th {{ background-color: #FFFFFF; color: #000000; font-weight: bold; padding: 5px; text-align: center; font-family: 'Cairo', sans-serif; }}
  td {{ padding: 4px 6px; vertical-align: top; background: #FFFFFF; color: #000000; }}
</style>
</head>
<body>

<!-- PAGE 1: TITLE -->
<div class="page">
  <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 10.5pt; line-height: 1.4; color: #000000; font-family: 'Cairo', sans-serif;">
    <div>الجمهـوريـــة اليمنية<br>وزارة التعليم العالي والبحث العلمي<br>جامعة صنعاء<br>كلية الحاسوب وتكنولوجيا المعلومات<br>قسم علوم الحاسوب</div>
    <div style="text-align: left; direction: ltr;">Republic of Yemen<br>Ministry of Higher Education<br>Sana'a University<br>Faculty of Computer & IT<br>Department of Computer Science</div>
  </div>

  <div style="text-align: center; margin-top: 70px; margin-bottom: 30px;">
    <div style="font-size: 22pt; font-weight: bold; color: #000000; font-family: 'Cairo', sans-serif;">مخططات وتصاميم هندسة البرمجيات</div>
    <div style="font-size: 16pt; font-weight: bold; color: #000000; margin-top: 8px; font-family: 'Cairo', sans-serif;">Software Engineering Diagrams Specification</div>
    <div style="font-size: 14pt; font-weight: bold; color: #000000; margin-top: 10px;">نظام إدارة الصيدلية الذكي (Smart Pharmacy Management System - SPMS)</div>
    <div style="font-size: 11pt; font-style: italic; color: #333333; margin-top: 14px;">
      مقتبس ومصمم وفق معايير مقرر هندسة البرمجيات (مربعات ودوائر بيضاء بسيطة — بدون ألوان)
    </div>
  </div>

  <div style="margin-top: 50px; border: 1.5px solid #000000; padding: 15px; background: #FFFFFF;">
    <div style="font-weight: bold; font-size: 12pt; margin-bottom: 8px; font-family: 'Cairo', sans-serif;">فهرس المخططات الهندسية المتضمنة:</div>
    <ul>
      <li><strong>شكل (3-1):</strong> مخطط السياق للنظام (Context Diagram - DFD Level 0).</li>
      <li><strong>شكل (3-2):</strong> مخطط تدفق البيانات التفصيلي (DFD Level 1 Diagram).</li>
      <li><strong>شكل (3-3):</strong> مخطط حالات الاستخدام للنظام (Use Case Diagram).</li>
      <li><strong>شكل (3-4):</strong> مخطط الكيانات والعلاقات العلائقي (ERD Diagram - 3NF).</li>
      <li><strong>شكل (3-5):</strong> مخطط الفئات كائنية التوجه (Class Diagram - OOP).</li>
      <li><strong>شكل (3-6):</strong> مخطط التتابع والتسلسل الزمني (Sequence Diagram).</li>
      <li><strong>شكل (3-7):</strong> مخطط النشاطات لعملية الصرف وفرز الصلاحيات (Activity Diagram).</li>
      <li><strong>شكل (3-8):</strong> مخطط الحالات لدورة حياة الدفعة الدوائية (State Chart Diagram).</li>
      <li><strong>جدول (3-1):</strong> قالب التوصيف القياسي لحالة الاستخدام (Use Case Specification Template).</li>
    </ul>
  </div>

  <div style="text-align: center; margin-top: 60px; font-size: 10.5pt; color: #333333;">
    العام الأكاديمي 2026 م / 1447 هـ — جامعة صنعاء
  </div>
</div>

<!-- PAGE 2: CONTEXT DIAGRAM -->
<div class="page">
  <div class="page-header-line"><div>مخطط السياق للنظام (DFD Level 0)</div><div>1</div></div>
  <h1>1. مخطط السياق للنظام (Context Diagram - DFD Level 0)</h1>
  <p>يمثل مخطط السياق أعلى مستوى تجريدي لتدفق البيانات بالنظام، وتظهر الصيدلية كعملية مركزية دائرة بيضاء (0) والكيانات الخارجية كمستطيلات بيضاء:</p>
  <div class="img-box">
    <img src="{img_rel_dir}/01_Context_Diagram_DFD0.png" alt="Context Diagram">
    <div class="caption">شكل (3-1): مخطط السياق للنظام (Context Diagram - DFD Level 0)</div>
  </div>
  <p><strong>عناصر المخطط:</strong> دائرة النظام المركزية (0: SPMS)، والمربعات الخارجية: مدير الصيدلية، الصيدلي المناوب، الموردون، والمريض/العميل، مع أسهم سوداء تبين تدفقات الأوامر والاستعلامات والفواتير والتقارير.</p>
</div>

<!-- PAGE 3: DFD LEVEL 1 -->
<div class="page">
  <div class="page-header-line"><div>مخطط تدفق البيانات التفصيلي (DFD Level 1)</div><div>2</div></div>
  <h1>2. مخطط تدفق البيانات التفصيلي (DFD Level 1 Diagram)</h1>
  <p>تفكيك العملية المركزية إلى 4 عمليات رئيسية (1.0 إلى 4.0) بدوائر بيضاء مع 4 مخازن بيانات (Data Stores D1-D4):</p>
  <div class="img-box">
    <img src="{img_rel_dir}/02_DFD_Level1_Diagram.png" alt="DFD Level 1">
    <div class="caption">شكل (3-2): مخطط تدفق البيانات التفصيلي (DFD Level 1 Diagram)</div>
  </div>
  <p><strong>العمليات:</strong> 1.0 إدارة المخزون، 2.0 نقطة البيع والصرف، 3.0 محرك التنبؤ والذكاء الاصطناعي، 4.0 فرز الدفعات وإدارة الصلاحيات (FEFO). المخازن: D1 الأدوية، D2 الدفعات، D3 المبيعات، D4 سجل التنبؤات.</p>
</div>

<!-- PAGE 4: USE CASE DIAGRAM -->
<div class="page">
  <div class="page-header-line"><div>مخطط حالات الاستخدام (Use Case Diagram)</div><div>3</div></div>
  <h1>3. مخطط حالات الاستخدام للنظام (Use Case Diagram)</h1>
  <p>يوضح حدود النظام كمستطيل أبيض كبير يحتوي على حالات الاستخدام كأشكال بيضاوية بيضاء نقية وعلاقات الفواعل:</p>
  <div class="img-box">
    <img src="{img_rel_dir}/03_UseCase_Diagram.png" alt="Use Case Diagram">
    <div class="caption">شكل (3-3): مخطط حالات الاستخدام للنظام (Use Case Diagram)</div>
  </div>
  <p><strong>الفواعل:</strong> الصيدلي (يسار)، مدير الصيدلية (أعلى اليمين)، أمين المخزن (أسفل اليمين). العلاقات تشمل &lt;&lt;include&gt;&gt; لفرز FEFO وكشف الشذوذ، و &lt;&lt;extend&gt;&gt; لتوصيات السلة Apriori.</p>
</div>

<!-- PAGE 5: USE CASE SPECIFICATION -->
<div class="page">
  <div class="page-header-line"><div>قالب التوصيف القياسي لحالة الاستخدام</div><div>4</div></div>
  <h1>قالب التوصيف القياسي لحالة الاستخدام (قالب التوصيف.docx)</h1>
  <p>مقتبس من قالب التوصيف المعتمد في مقرر هندسة البرمجيات (Lect_6):</p>
  <table>
    <tr><th style="width:25%;">Unique ID</th><td>UC-03</td></tr>
    <tr><th>Use case name :</th><td>معالجة عملية البيع والصرف وتطبيق فرز FEFO (Process Sale)</td></tr>
    <tr><th>Area: Actors :</th><td>الصيدلي المناوب (Pharmacist)</td></tr>
    <tr><th>Description:</th><td>تمكين الصيدلي من البحث الذكي عن الدواء، ترشيح الدفعة الأقرب انتهاءً آلياً، فحص الشذوذ، وتأكيد الفاتورة.</td></tr>
    <tr><th>Triggering Event:</th><td>طلب العميل شراء دواء أو صرف وصفة طبية.</td></tr>
    <tr><th>Trigger Type:</th><td>External (طلب مباشر خارجي)</td></tr>
    <tr><th>Information Of Steps:</th><td>1. إدخال استعلام البحث.<br>2. تشغيل البحث الذكي وتحديد الصنف.<br>3. تطبيق فرز الدفعات الأقرب انتهاءً (FEFO).<br>4. فحص الشذوذ Z-Score.<br>5. توليد توصيات السلة Apriori.<br>6. تأكيد الفاتورة والخصم من الدفعة وطباعة الإيصال.</td></tr>
    <tr><th>Preconditions:</th><td>تسجيل دخول الصيدلي للنظام، وتوفر رصيد دوائي صالح في المخزون.</td></tr>
    <tr><th>Post conditions:</th><td>خصم الكمية المصروفة من رصيد الدفعة، إنشاء سجل مبيعات جديد، وطباعة الفاتورة.</td></tr>
    <tr><th>Assumptions:</th><td>استقرار خادم التطبيقات وقاعدة البيانات المحلية.</td></tr>
    <tr><th>Requirements Met:</th><td>أتمتة الصرف، خفض تلف الأدوية 30%، وحماية المريض من الأدوية منتهية الصلاحية.</td></tr>
    <tr><th>Priority / Risk:</th><td>Priority: High (قصوى) &nbsp;|&nbsp; Risk: Low (منخفض)</td></tr>
  </table>
</div>

<!-- PAGE 6: CLASS DIAGRAM -->
<div class="page">
  <div class="page-header-line"><div>مخطط الفئات كائنية التوجه (Class Diagram)</div><div>5</div></div>
  <h1>4. مخطط الفئات كائنية التوجه (Class Diagram - OOP)</h1>
  <p>المعمارية كائنية التوجه ومبدأ المسؤولية الواحدة (SRP) بمستطيلات بيضاء نقية ثلاثية الأقسام:</p>
  <div class="img-box">
    <img src="{img_rel_dir}/05_Class_Diagram.png" alt="Class Diagram">
    <div class="caption">شكل (3-5): مخطط الفئات كائنية التوجه (Class Diagram - OOP)</div>
  </div>
  <p><strong>الفئات:</strong> ModelPredictor (محرك الذكاء الاصطناعي المركزي)، Medicine، Batch، Sale، SaleItem، و User مع توضيح السمات (Attributes) والعمليات (Methods) وعلاقات التجميع والتضمين.</p>
</div>

<!-- PAGE 7: SEQUENCE DIAGRAM -->
<div class="page">
  <div class="page-header-line"><div>مخطط التتابع والتسلسل الزمني (Sequence Diagram)</div><div>6</div></div>
  <h1>5. مخطط التتابع والتسلسل الزمني (Sequence Diagram)</h1>
  <p>التفاعل الزمني التتابعي أثناء معالجة الصرف وتطبيق FEFO والبحث الذكي:</p>
  <div class="img-box">
    <img src="{img_rel_dir}/06_Sequence_Diagram.png" alt="Sequence Diagram">
    <div class="caption">شكل (3-6): مخطط التتابع والتسلسل الزمني (Sequence Diagram)</div>
  </div>
  <p><strong>الكائنات:</strong> :الصيدلي، :POS_UI، :ServerAPI، :ModelPredictor، و :Database مع أشرطة التنفيذ البيضاء وأسهم النداء والرد المتسلسلة زمنياً.</p>
</div>

<!-- PAGE 8: ACTIVITY DIAGRAM -->
<div class="page">
  <div class="page-header-line"><div>مخطط النشاطات (Activity Diagram)</div><div>7</div></div>
  <h1>6. مخطط النشاطات وتدفق العمليات (Activity Diagram)</h1>
  <p>تدفق خطوات الصرف ومنطق التحقق من الصلاحيات والبدائل والقرارات المشروطة:</p>
  <div class="img-box">
    <img src="{img_rel_dir}/07_Activity_Diagram.png" alt="Activity Diagram">
    <div class="caption">شكل (3-7): مخطط النشاطات لعملية الصرف وفرز الصلاحيات (Activity Diagram)</div>
  </div>
  <p><strong>العناصر:</strong> مستطيلات مستديرة بيضاء للأنشطة، معينات بيضاء للقرارات، نقطة بداية سوداء ونقطة نهاية مزدوجة (Bullseye).</p>
</div>

<!-- PAGE 9: STATE CHART DIAGRAM -->
<div class="page">
  <div class="page-header-line"><div>مخطط الحالات (State Chart Diagram)</div><div>8</div></div>
  <h1>7. مخطط الحالات لدورة حياة الدفعة الدوائية (State Chart Diagram)</h1>
  <p>التحولات السلوكية لحالات كائن الدفعة (Batch Lifecycle) وفق منطق FEFO بمستطيلات بيضاء مستديرة:</p>
  <div class="img-box">
    <img src="{img_rel_dir}/08_State_Chart_Diagram.png" alt="State Chart Diagram">
    <div class="caption">شكل (3-8): مخطط الحالات لدورة حياة الدفعة الدوائية (State Chart Diagram)</div>
  </div>
  <p><strong>الحالات:</strong> مستلمة بالمستودع -> صالحة بالمخزون -> تنبيه مبكر -> أولوية متوسطة -> أولوية حرجة / عاجل -> مصروفة بالكامل أو تالفة ومحجوزة للإتلاف.</p>
</div>

<!-- PAGE 10: ERD DIAGRAM -->
<div class="page">
  <div class="page-header-line"><div>مخطط الكيانات والعلاقات (ERD Diagram)</div><div>9</div></div>
  <h1>8. مخطط الكيانات والعلاقات (ERD Diagram - 3NF)</h1>
  <p>بنية قاعدة البيانات العلائقية بالنموذج العادي الثالث بمستطيلات ومعينات بيضاء نقية:</p>
  <div class="img-box">
    <img src="{img_rel_dir}/04_ERD_Diagram.png" alt="ERD Diagram">
    <div class="caption">شكل (3-4): مخطط الكيانات والعلاقات (ERD Diagram - 3NF)</div>
  </div>
  <p><strong>الجداول والعلاقات:</strong> MEDICINES (1:M) BATCHES، SALES (1:M) SALE_ITEMS، BATCHES (1:M) SALE_ITEMS، و USERS (1:M) SALES.</p>
</div>

</body>
</html>
"""
    with open(html_path, "w", encoding="utf-8") as f:
        f.write(html_content)

    if os.path.exists(chrome_path):
        cmd = [
            chrome_path,
            "--headless",
            "--disable-gpu",
            f"--print-to-pdf={pdf_path}",
            "--no-pdf-header-footer",
            html_path
        ]
        subprocess.run(cmd, capture_output=True)
        print(f"[PDF Showcase Generated]: {pdf_path}")

if __name__ == "__main__":
    build_word()
    build_pdf()
