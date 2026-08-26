import os
import sys
import datetime
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.lib.units import mm
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, Preformatted, HRFlowable
)
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    """
    Two-pass canvas to dynamically compute and print 'Page X of Y' and header on every page.
    """
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_header_footer(num_pages)
            super().showPage()
        super().save()

    def draw_header_footer(self, page_count):
        if self._pageNumber == 1:
            # Skip header/footer on cover page
            return
        
        self.saveState()
        self.setFont("Helvetica-Bold", 8)
        self.setFillColor(colors.HexColor("#4B5563"))
        
        # Header
        self.drawString(16 * mm, 287 * mm, "source code onyxtulungagung.id")
        self.setFont("Helvetica", 8)
        self.drawRightString(194 * mm, 287 * mm, "Dokumen Pendaftaran Hak Cipta (HKI) - DJKI")
        
        self.setStrokeColor(colors.HexColor("#CBD5E1"))
        self.setLineWidth(0.5)
        self.line(16 * mm, 284 * mm, 194 * mm, 284 * mm)
        
        # Footer
        self.line(16 * mm, 14 * mm, 194 * mm, 14 * mm)
        self.drawString(16 * mm, 9 * mm, "E-SCM Marmer Tulungagung - Confidential & Copyright Protected")
        page_text = f"Halaman {self._pageNumber} dari {page_count}"
        self.drawRightString(194 * mm, 9 * mm, page_text)
        
        self.restoreState()


def get_core_files(root_dir):
    """
    Collect all core source code files in a structured order.
    """
    sections = [
        {
            "category": "1. AI Machine Learning Forecasting Microservice",
            "files": [
                os.path.join(root_dir, "forecasting_service", "main.py"),
                os.path.join(root_dir, "forecasting_service", "requirements.txt"),
            ]
        },
        {
            "category": "2. Application Controllers (Business Logic)",
            "files": sorted([
                os.path.join(root_dir, "app", "Http", "Controllers", f)
                for f in os.listdir(os.path.join(root_dir, "app", "Http", "Controllers"))
                if f.endswith(".php")
            ])
        },
        {
            "category": "3. Data Models & Eloquent ORM",
            "files": sorted([
                os.path.join(root_dir, "app", "Models", f)
                for f in os.listdir(os.path.join(root_dir, "app", "Models"))
                if f.endswith(".php")
            ])
        },
        {
            "category": "4. Services, Providers & Middlewares",
            "files": [
                os.path.join(root_dir, "app", "Services", "CodeGeneratorService.php"),
                os.path.join(root_dir, "app", "Http", "Middleware", "RoleMiddleware.php"),
                os.path.join(root_dir, "app", "Providers", "AppServiceProvider.php"),
            ]
        },
        {
            "category": "5. Routing Definitions & API Endpoints",
            "files": [
                os.path.join(root_dir, "routes", "web.php"),
                os.path.join(root_dir, "routes", "api.php"),
                os.path.join(root_dir, "routes", "console.php"),
            ] + (
                sorted([
                    os.path.join(root_dir, "routes", "modules", f)
                    for f in os.listdir(os.path.join(root_dir, "routes", "modules"))
                    if f.endswith(".php")
                ]) if os.path.exists(os.path.join(root_dir, "routes", "modules")) else []
            )
        },
        {
            "category": "6. Database Schema & Migrations",
            "files": sorted([
                os.path.join(root_dir, "database", "migrations", f)
                for f in os.listdir(os.path.join(root_dir, "database", "migrations"))
                if f.endswith(".php")
            ])
        },
        {
            "category": "7. User Interface Templates (Blade Views)",
            "files": sorted([
                os.path.join(dp, f)
                for dp, dn, filenames in os.walk(os.path.join(root_dir, "resources", "views"))
                for f in filenames if f.endswith(".blade.php")
            ])
        }
    ]
    return sections


def format_code_lines(content, max_line_len=108):
    """
    Format source code with line numbers and word wrap / line splitting.
    """
    raw_lines = content.splitlines()
    formatted = []
    for idx, line in enumerate(raw_lines, 1):
        clean_line = line.replace('\t', '    ')
        prefix = f"{idx:04d} | "
        
        if len(clean_line) <= max_line_len:
            formatted.append(f"{prefix}{clean_line}")
        else:
            chunks = [clean_line[i:i+max_line_len] for i in range(0, len(clean_line), max_line_len)]
            formatted.append(f"{prefix}{chunks[0]}")
            for continuation in chunks[1:]:
                formatted.append(f"     |   -> {continuation}")
    return "\n".join(formatted)


def build_pdf(root_dir, output_pdf_path):
    doc = SimpleDocTemplate(
        output_pdf_path,
        pagesize=A4,
        leftMargin=14 * mm,
        rightMargin=14 * mm,
        topMargin=18 * mm,
        bottomMargin=18 * mm,
    )
    
    styles = getSampleStyleSheet()
    
    style_cover_title = ParagraphStyle(
        'CoverTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=24,
        leading=30,
        alignment=1,
        textColor=colors.HexColor("#0F172A")
    )
    
    style_cover_subtitle = ParagraphStyle(
        'CoverSubtitle',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=11.5,
        leading=16,
        alignment=1,
        textColor=colors.HexColor("#475569")
    )
    
    style_h1 = ParagraphStyle(
        'SectionH1',
        parent=styles['Heading1'],
        fontName='Helvetica-Bold',
        fontSize=13,
        leading=17,
        textColor=colors.HexColor("#1E3A8A"),
        spaceBefore=14,
        spaceAfter=6,
        keepWithNext=True
    )
    
    style_file_header = ParagraphStyle(
        'FileHeader',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=8.5,
        leading=11,
        textColor=colors.HexColor("#0F172A"),
        spaceBefore=0,
        spaceAfter=0,
        keepWithNext=True
    )
    
    style_code = ParagraphStyle(
        'CodeStyle',
        parent=styles['Normal'],
        fontName='Courier',
        fontSize=6.5,
        leading=8.2,
        textColor=colors.HexColor("#1E293B"),
        spaceBefore=3,
        spaceAfter=8
    )
    
    style_toc_item = ParagraphStyle(
        'TOCItem',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=8.5,
        leading=11,
        textColor=colors.HexColor("#334155")
    )
    
    elements = []
    
    # ==========================================
    # 1. COVER PAGE
    # ==========================================
    elements.append(Spacer(1, 40 * mm))
    elements.append(Paragraph("source code onyxtulungagung.id", style_cover_title))
    elements.append(Spacer(1, 5 * mm))
    elements.append(HRFlowable(width="80%", thickness=2.5, color=colors.HexColor("#2563EB"), spaceAfter=15, spaceBefore=5))
    elements.append(Paragraph(
        "Sistem Informasi E-Supply Chain Management (E-SCM) Klaster IKM Kerajinan Marmer dan Batu Kali Tulungagung Integrasi AI Forecasting",
        style_cover_subtitle
    ))
    elements.append(Spacer(1, 20 * mm))
    
    info_data = [
        [Paragraph("<b>Judul Dokumen</b>", style_toc_item), Paragraph("source code onyxtulungagung.id", style_toc_item)],
        [Paragraph("<b>Jenis Dokumen</b>", style_toc_item), Paragraph("Lampiran Kode Sumber (Source Code) Hak Cipta", style_toc_item)],
        [Paragraph("<b>Kategori Ciptaan</b>", style_toc_item), Paragraph("Program Komputer / Perangkat Lunak (Software)", style_toc_item)],
        [Paragraph("<b>Platform & Domain</b>", style_toc_item), Paragraph("onyxtulungagung.id (Web SCM & AI Forecasting)", style_toc_item)],
        [Paragraph("<b>Stack Teknologi</b>", style_toc_item), Paragraph("Laravel 11, PHP 8.3, Python 3.12 (FastAPI ML), MySQL 8.0, Tailwind CSS", style_toc_item)],
        [Paragraph("<b>Mitra Penerapan IKM</b>", style_toc_item), Paragraph("UD Cahaya Onix & UD Putra Abadi (Tulungagung, Jawa Timur)", style_toc_item)],
        [Paragraph("<b>Instansi / Pengusul</b>", style_toc_item), Paragraph("Tim Peneliti LPPM & Pengembang Sistem E-SCM Marmer", style_toc_item)],
        [Paragraph("<b>Tanggal Ekspor</b>", style_toc_item), Paragraph(datetime.datetime.now().strftime("%d %B %Y"), style_toc_item)],
    ]
    info_table = Table(info_data, colWidths=[45 * mm, 130 * mm])
    info_table.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, -1), colors.HexColor("#F8FAFC")),
        ('BOX', (0, 0), (-1, -1), 1, colors.HexColor("#CBD5E1")),
        ('INNERGRID', (0, 0), (-1, -1), 0.5, colors.HexColor("#E2E8F0")),
        ('TOPPADDING', (0, 0), (-1, -1), 5),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 5),
        ('LEFTPADDING', (0, 0), (-1, -1), 8),
        ('RIGHTPADDING', (0, 0), (-1, -1), 8),
    ]))
    elements.append(info_table)
    
    elements.append(Spacer(1, 25 * mm))
    elements.append(Paragraph(
        "<i>Dokumen ini memuat kumpulan kode sumber representatif dari seluruh modul inti sistem untuk keperluan registrasi Hak Kekayaan Intelektual (HKI) pada Direktorat Jenderal Kekayaan Intelektual (DJKI) Kementerian Hukum dan HAM Republik Indonesia.</i>",
        ParagraphStyle('CoverNotice', parent=styles['Normal'], fontName='Helvetica-Oblique', fontSize=8, leading=11, alignment=1, textColor=colors.HexColor("#64748B"))
    ))
    
    elements.append(PageBreak())
    
    # ==========================================
    # 2. TABLE OF CONTENTS / SUMMARY
    # ==========================================
    elements.append(Paragraph("Daftar Modul & Ringkasan Source Code", style_h1))
    elements.append(HRFlowable(width="100%", thickness=1, color=colors.HexColor("#1E3A8A"), spaceAfter=10, spaceBefore=2))
    
    sections = get_core_files(root_dir)
    total_files = 0
    total_lines = 0
    
    toc_summary = []
    toc_summary.append([
        Paragraph("<b>No</b>", style_toc_item),
        Paragraph("<b>Kategori Modul / Direktori</b>", style_toc_item),
        Paragraph("<b>Jumlah File</b>", style_toc_item),
        Paragraph("<b>Total Baris</b>", style_toc_item)
    ])
    
    file_inventory = []
    
    for s_idx, sec in enumerate(sections, 1):
        sec_files = [f for f in sec['files'] if os.path.isfile(f)]
        sec_lines = 0
        for fpath in sec_files:
            try:
                with open(fpath, 'r', encoding='utf-8', errors='ignore') as f:
                    sec_lines += sum(1 for _ in f)
            except Exception:
                pass
        
        total_files += len(sec_files)
        total_lines += sec_lines
        
        toc_summary.append([
            Paragraph(str(s_idx), style_toc_item),
            Paragraph(f"<b>{sec['category']}</b>", style_toc_item),
            Paragraph(str(len(sec_files)), style_toc_item),
            Paragraph(f"{sec_lines:,} baris", style_toc_item)
        ])
        
        file_inventory.append((sec['category'], sec_files))
    
    # Total row
    toc_summary.append([
        Paragraph("", style_toc_item),
        Paragraph("<b>TOTAL KESELURUHAN</b>", style_toc_item),
        Paragraph(f"<b>{total_files} file</b>", style_toc_item),
        Paragraph(f"<b>{total_lines:,} baris</b>", style_toc_item)
    ])
    
    toc_table = Table(toc_summary, colWidths=[10 * mm, 105 * mm, 28 * mm, 32 * mm])
    toc_table.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.HexColor("#EEF2F6")),
        ('BACKGROUND', (0, -1), (-1, -1), colors.HexColor("#F1F5F9")),
        ('BOX', (0, 0), (-1, -1), 1, colors.HexColor("#94A3B8")),
        ('INNERGRID', (0, 0), (-1, -1), 0.5, colors.HexColor("#CBD5E1")),
        ('TOPPADDING', (0, 0), (-1, -1), 4),
        ('BOTTOMPADDING', (0, 0), (-1, -1), 4),
        ('LEFTPADDING', (0, 0), (-1, -1), 6),
        ('RIGHTPADDING', (0, 0), (-1, -1), 6),
    ]))
    elements.append(toc_table)
    elements.append(Spacer(1, 10 * mm))
    
    elements.append(PageBreak())
    
    # ==========================================
    # 3. LISTING CODE PER SECTION & FILE
    # ==========================================
    for category_name, files in file_inventory:
        elements.append(Paragraph(category_name, style_h1))
        elements.append(HRFlowable(width="100%", thickness=1, color=colors.HexColor("#2563EB"), spaceAfter=8, spaceBefore=2))
        
        for file_path in files:
            rel_path = os.path.relpath(file_path, root_dir).replace('\\', '/')
            try:
                with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                    content = f.read()
            except Exception as e:
                content = f"// Gagal membaca file: {e}"
            
            line_count = len(content.splitlines())
            file_size_kb = os.path.getsize(file_path) / 1024
            
            header_text = f"📄 <b>File:</b> {rel_path} &nbsp;&nbsp;|&nbsp;&nbsp; <b>Ukuran:</b> {file_size_kb:.1f} KB &nbsp;&nbsp;|&nbsp;&nbsp; <b>Baris:</b> {line_count}"
            
            header_table = Table(
                [[Paragraph(header_text, style_file_header)]],
                colWidths=[182 * mm]
            )
            header_table.setStyle(TableStyle([
                ('BACKGROUND', (0, 0), (-1, -1), colors.HexColor("#E2E8F0")),
                ('BOX', (0, 0), (-1, -1), 0.5, colors.HexColor("#94A3B8")),
                ('TOPPADDING', (0, 0), (-1, -1), 3),
                ('BOTTOMPADDING', (0, 0), (-1, -1), 3),
                ('LEFTPADDING', (0, 0), (-1, -1), 6),
                ('RIGHTPADDING', (0, 0), (-1, -1), 6),
            ]))
            
            formatted_code = format_code_lines(content, max_line_len=112)
            code_pre = Preformatted(formatted_code, style_code)
            
            elements.append(header_table)
            elements.append(code_pre)
            elements.append(HRFlowable(width="100%", thickness=0.5, color=colors.HexColor("#CBD5E1"), spaceAfter=6, spaceBefore=2))
            
        elements.append(PageBreak())
        
    if isinstance(elements[-1], PageBreak):
        elements.pop()
        
    print(f"Compiling PDF with {total_files} files and {total_lines} lines of code...")
    doc.build(elements, canvasmaker=NumberedCanvas)
    print(f"Successfully generated: {output_pdf_path}")

if __name__ == "__main__":
    project_root = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    output_pdf = os.path.join(project_root, "source_code_e-scm_marmer.pdf")
    build_pdf(project_root, output_pdf)
