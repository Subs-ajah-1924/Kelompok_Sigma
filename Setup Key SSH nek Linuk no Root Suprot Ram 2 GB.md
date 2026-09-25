
Setup SSH Key Fedora Linux(Luweh enak nggowo iki)

**1.Cek wes ndue file .ssh taurong,tek rak ono,nggowo iki:**

a.cek .ssh

	ls -la ~/.ssh

b.gawe SSH anyar

	ssh-keygen -t ed25519 -C "emailkamu@example.com"

nggowo ed25519 paleng rekomendasi,bariku garek enter enter wae

**2.Jalankan SSH Agent(Penyimpanan Kunci Sementara),trs nggo Key SSH ne**

a.jalankan SSH agent

	eval "$(ssh-agent -s)"

b.gunakan key SSH yang dibuat tadi

	ssh-add ~/.ssh/id_ed25519

c.cek SSH wes masoh nek Agent

	ssh-add -l

geneo aren ngurepno agent(penyimpanan sementara)?ben iso di tambahi key ngo njero agent e ben di simpen nek njerone.**nah nko SSH ne dinggo kanggo identitas komputermu pas push ngo GitHub**

**3.Copy seluruh output dari SSH tadi**

	cat ~/.ssh/id_ed25519.pub

pas metu output e,copy output e

**4.Tambahkan key ngo GitHub**
lokasi ne iku nek settings/keys -> Klik New SSH Key -> trs ini nama key ne,misal jengen leptopmu karo paste key seng bar mbok copy mou -> save

**5.Tes Koneksi ngo GitHub**

	ssh -T git@github.com

tek muncul "Are you sure you want to continue connecting (yes/no)?" -> klik yea

**6.Remote Ngenggo SHH**

	git remote set-url origin git@github.com:Subs-ajah-1924/My_Python_Gwej.git

-Cek Remote : 

	git remote -v

haruse ono output username karo jengen repomu 