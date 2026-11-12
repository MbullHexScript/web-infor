from pathlib import Path
from PIL import Image, ImageDraw


def make(input_dir: Path, output_dir: Path, prefix: str):
    output_dir.mkdir(parents=True, exist_ok=True)
    pages = sorted(input_dir.glob("page-*.png"), key=lambda p: int(p.stem.split("-")[-1]))
    per_sheet = 6
    cols = 3
    thumb_w = 430
    gap = 24
    label_h = 28
    for start in range(0, len(pages), per_sheet):
        batch = pages[start:start + per_sheet]
        thumbs = []
        for path in batch:
            img = Image.open(path).convert("RGB")
            h = round(img.height * thumb_w / img.width)
            thumbs.append((path, img.resize((thumb_w, h))))
        rows = (len(thumbs) + cols - 1) // cols
        cell_h = max(i.height for _, i in thumbs) + label_h
        canvas = Image.new("RGB", (cols * thumb_w + (cols + 1) * gap,
                                   rows * cell_h + (rows + 1) * gap), "white")
        draw = ImageDraw.Draw(canvas)
        for idx, (path, img) in enumerate(thumbs):
            row, col = divmod(idx, cols)
            x = gap + col * (thumb_w + gap)
            y = gap + row * cell_h
            draw.text((x, y), f"Page {path.stem.split('-')[-1]}", fill="black")
            canvas.paste(img, (x, y + label_h))
        out = output_dir / f"{prefix}-{start // per_sheet + 1}.png"
        canvas.save(out, quality=92)
        print(out)


root = Path(r"D:\Website Informatika\docs\pembelajaran-mesin")
make(root / "qa_modul_v3", root / "qa_contact_v3", "modul")
make(root / "qa_panduan_v2", root / "qa_contact_v3", "panduan")
