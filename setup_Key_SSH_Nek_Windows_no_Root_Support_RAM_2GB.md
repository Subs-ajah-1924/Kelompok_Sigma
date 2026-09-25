1. Buka PowerShell / Git Bash

Cek SSH:

    ssh -V

tek muncul versi OpenSSH, berarti ws ndue

iki misal rak ndue
2. Buat SSH key

    ssh-keygen -t ed25519 -C "email-github-kamu@example.com"

angger enter enter wae kabeh

3. Jalankan SSH agent

Di PowerShell:

    Get-Service ssh-agent | Set-Service -StartupType Automatic 
    
    Start-Service ssh-agent

Kemudian:

    ssh-add $env:USERPROFILE\.ssh\id_ed25519

4. Ambil public key

    Get-Content $env:USERPROFILE\.ssh\id_ed25519.pub

Hasilnya:

    ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAA... email@example.com

Copy kabeh

5. Tambahno ke GitHub

GitHub → Settings → SSH and GPG keys → New SSH key

Kasih nama:

Windows Laptop

Paste public key → Add SSH key.

6. Tes

    ssh -T git@github.com

tek iso bariku muncul kyok ngene:

    Hi subahehe! You've successfully authenticated...
