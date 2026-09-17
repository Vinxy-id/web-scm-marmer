$ErrorActionPreference = "Stop"

$docxRelative = "Docs/laporan_kegiatan/DOCX/Buku_Manual_Pengguna_ESCM_Marmer.docx"
$pdfRelative = "Docs/laporan_kegiatan/PDF/Buku_Manual_Pengguna_ESCM_Marmer.pdf"

$docxPath = (Resolve-Path $docxRelative).Path
$pdfDir = (Resolve-Path "Docs/laporan_kegiatan/PDF").Path
$pdfPath = [System.IO.Path]::Combine($pdfDir, "Buku_Manual_Pengguna_ESCM_Marmer.pdf")

Write-Host "Opening DOCX: $docxPath"
Write-Host "Target PDF: $pdfPath"

$word = New-Object -ComObject Word.Application
$word.Visible = $false
try {
    $doc = $word.Documents.Open($docxPath, $false, $true) # Open ReadOnly
    # 17 = wdFormatPDF
    $doc.SaveAs([ref]$pdfPath, [ref]17)
    $doc.Close([ref]$false)
    Write-Host "SUCCESS: PDF created successfully at: $pdfPath"
}
finally {
    $word.Quit()
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($word) | Out-Null
    [System.GC]::Collect()
    [System.GC]::WaitForPendingFinalizers()
}
