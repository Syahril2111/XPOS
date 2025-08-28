@echo off
echo ========================================
echo   Deploy Website XPOS ke GitHub
echo ========================================

REM Set identitas Git (sekali aja, bisa hapus setelahnya)
git config --global user.name "Syahril2111"
git config --global user.email "alfamiind@gmail.com"

REM Inisialisasi repo jika belum ada
if not exist ".git" (
    git init
)

REM Tambah semua file
git add .

REM Commit pertama jika belum ada
git commit -m "Initial deploy" || echo Sudah ada commit, skip

REM Pastikan branch pakai main
git branch -M main

REM Set remote origin
git remote remove origin >nul 2>&1
git remote add origin https://github.com/Syahril2111/XPOS.git

REM Push ke GitHub
git push -u origin main

echo ========================================
echo   Deploy Berhasil!
echo   Website tersedia di:
echo   https://Syahril2111.github.io/XPOS/
echo ========================================
pause
