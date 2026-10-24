import json, sys, os
sys.path.insert(0, os.path.dirname(__file__))
import elib
elib.load_media(sys.argv[1])
import pages
out = sys.argv[2]
os.makedirs(out, exist_ok=True)
for slug, fn in pages.ALL.items():
    data = fn()
    json.dump(data, open(os.path.join(out, slug + '.json'), 'w'), ensure_ascii=False)
    print(slug, len(json.dumps(data)) // 1024, 'KB')
