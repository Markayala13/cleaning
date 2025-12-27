#!/usr/bin/env python3
from PIL import Image
import os
import glob

# Directorios
source_dir = r"E:\PROYECTOS\Cleaning\pics"
target_dir = r"E:\PROYECTOS\Cleaning\html\images\project\completed"

# Crear directorio de destino si no existe
os.makedirs(target_dir, exist_ok=True)

# Obtener todas las imágenes jpeg/jpg (excepto las de hero)
image_files = glob.glob(os.path.join(source_dir, "*.jpeg")) + glob.glob(os.path.join(source_dir, "*.jpg"))

print(f"Found {len(image_files)} images to compress...")

for i, img_path in enumerate(image_files, 1):
    try:
        # Abrir imagen
        img = Image.open(img_path)

        # Convertir a RGB si es necesario (para JPEG)
        if img.mode in ('RGBA', 'P', 'LA'):
            img = img.convert('RGB')

        # Redimensionar si es muy grande (max 1920px ancho manteniendo aspecto)
        max_width = 1920
        if img.width > max_width:
            ratio = max_width / img.width
            new_size = (max_width, int(img.height * ratio))
            img = img.resize(new_size, Image.Resampling.LANCZOS)

        # Nombre de salida simplificado
        output_name = f"project-{i}.jpg"
        output_path = os.path.join(target_dir, output_name)

        # Guardar con compresión optimizada (calidad 92 - excelente balance)
        img.save(output_path, 'JPEG', quality=92, optimize=True)

        original_size = os.path.getsize(img_path) / 1024  # KB
        compressed_size = os.path.getsize(output_path) / 1024  # KB
        reduction = ((original_size - compressed_size) / original_size) * 100

        print(f"{i}/{len(image_files)}: {output_name} - {original_size:.1f}KB -> {compressed_size:.1f}KB ({reduction:.1f}% reduction)")

    except Exception as e:
        print(f"Error processing {img_path}: {e}")

print(f"\nDone! Compressed {len(image_files)} images to {target_dir}")
