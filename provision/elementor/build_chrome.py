import json, os, sys
sys.path.insert(0, os.path.dirname(__file__))
import elib
elib.load_media(sys.argv[1])
import chrome_pages
out = sys.argv[2]
os.makedirs(out, exist_ok=True)
for name in ('header', 'footer'):
    data = getattr(chrome_pages, name)()
    json.dump(data, open(os.path.join(out, name + '.json'), 'w'), ensure_ascii=False)
    print(name, len(json.dumps(data)))
