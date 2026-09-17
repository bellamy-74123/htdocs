-- -*- coding: utf-8 -*-
USE pharmacy_db;

UPDATE medicines SET description = 'علاج فعال لحموضة وقرحة المعدة والارتجاع المريئي الحاد.' WHERE id = 43;
UPDATE medicines SET description = 'علاج خافض لدهون الدم والكوليسترول والدهون الثلاثية.' WHERE id = 44;
UPDATE medicines SET description = 'بخاخ موسع للشعب الهوائية والتهابات الربو المزمن وحساسية الصدر.' WHERE id = 45;
UPDATE medicines SET description = 'علاج سريع وفعال للإسهال الحاد وتنظيم حركة الأمعاء والنزلات المعوية.' WHERE id = 46;
UPDATE medicines SET description = 'علاج موضعي لالتهابات الجلد والحكة والحساسية الجلدية والإكزيما.' WHERE id = 47;

-- تحديث فلاجيل 500 ليشمل النزلات المعوية وتطهير الأمعاء والإسهال
UPDATE medicines SET description = 'مطهر ومضاد للطفيليات والبكتيريا اللاهوائية والنزلات المعوية وعلاج الإسهال.' WHERE id = 18;
