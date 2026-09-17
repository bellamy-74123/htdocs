# -*- coding: utf-8 -*-
import os, sys, shutil

PROJECT_ROOT = os.path.dirname(os.path.abspath(__file__))
HTDOCS_DIR = r"C:\xampp\htdocs\spms_showcase"
os.makedirs(HTDOCS_DIR, exist_ok=True)

print("Writing showcase website builder...")
