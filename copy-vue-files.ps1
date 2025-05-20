# Script PowerShell pour copier les fichiers Vue du répertoire d'origine vers l'application Laravel

# Chemin source et destination
$sourceDir = "C:\xampp\htdocs\EC2\!fichiersvue3\src"
$destDir = "C:\xampp\htdocs\EC2\resources\js\src"

# Créer le répertoire de destination s'il n'existe pas
if (!(Test-Path -Path $destDir)) {
    New-Item -ItemType Directory -Force -Path $destDir
}

# Copier récursivement tous les fichiers et dossiers
Write-Output "Copie des fichiers Vue de $sourceDir vers $destDir"
Copy-Item -Path "$sourceDir\*" -Destination $destDir -Recurse -Force

# Copier les images du dossier public
$sourcePublicDir = "C:\xampp\htdocs\EC2\!fichiersvue3\public"
$destPublicDir = "C:\xampp\htdocs\EC2\public\images"

# Créer le répertoire de destination pour les images s'il n'existe pas
if (!(Test-Path -Path $destPublicDir)) {
    New-Item -ItemType Directory -Force -Path $destPublicDir
}

# Copier les images
Write-Output "Copie des images de $sourcePublicDir vers $destPublicDir"
Copy-Item -Path "$sourcePublicDir\*" -Destination $destPublicDir -Recurse -Force

Write-Output "Copie terminée !"
