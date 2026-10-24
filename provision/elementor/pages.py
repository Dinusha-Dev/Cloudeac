"""Page definitions. Run build.py to turn them into Elementor JSON."""
from elib import *

QUOTE = '/contact-us/#quote'


def q(item):
    from urllib.parse import quote
    return '/contact-us/?item=%s#quote' % quote(item)


def check_circle_list(items, cls):
    return icon_list(items, cls, 'far fa-check-circle')


def value_cards(light=False):
    data = [('Outstanding quality', 'fas fa-award', 'Every job is checked against the proof so what you approve is what you receive.'),
            ('Latest technologies', 'fas fa-microchip', 'Offset presses, guillotine and die-cutting machines keep results sharp and consistent.'),
            ('Faster turnaround', 'fas fa-bolt', 'Streamlined production and responsive planning to meet your deadlines.'),
            ('Proactive service', 'fas fa-users', 'We solve practical problems before they reach the job, and stay in touch throughout.')]
    cards = []
    for i, (t, ic, d) in enumerate(data):
        cards.append(container([
            heading('0%d' % (i + 1), 'div', 'value-num'), icon(ic, 'value-ico'), heading(t, 'h3'), text(d)
        ], 'value-card rv d%d' % (i + 1) if i else 'value-card rv'))
    return container(cards, 'values-grid' + (' values-grid--light' if light else ''))


def machine_cards():
    data = [('machine-sorkz', 'Heidelberg SORKZ', 'Two-colour offset press for high-quality commercial printing.'),
            ('machine-gto', 'Heidelberg GTO', 'Compact, precise offset printing for stationery and short runs.'),
            ('machine-guillotine', 'Guillotine cutter', 'Programmable cutting for exact trim sizes on every job.'),
            ('machine-diecut', 'Die cutting machine', 'Cuts and creases cartons, boxes and special shapes.')]
    return container([container([image(k, 'large', 'machine-media'), container([heading(t, 'h3'), text(d)], 'machine-body')],
                                'machine rv' + (' d%d' % i if i else '')) for i, (k, t, d) in enumerate(data)], 'machines')


SECTORS = ['IT Companies', 'Banking & Finance', 'Consumer Electronics', 'Builders', 'Automotive', 'Telecom',
           'Educational Institutes', 'Media & Entertainment', 'Product Packaging']
INDUSTRIES = [('Hospitals', 'fas fa-hospital'), ('Healthcare', 'fas fa-heartbeat'), ('Home Appliance', 'fas fa-blender'),
              ('Food & Beverages', 'fas fa-glass-martini-alt'), ('Resorts & Spa', 'fas fa-umbrella-beach'),
              ('Cosmetics & Soaps', 'fas fa-pump-soap'), ('Ayurvedic', 'fas fa-leaf'), ('Gherkin Industries', 'fas fa-seedling')]


def industry_grid(dark=False):
    return container([container([icon(ic, 'industry-ico'), heading(n, 'span', 'industry-name')], 'industry rv' + (' d%d' % (i % 5 + 1) if i % 5 else ''))
                      for i, (n, ic) in enumerate(INDUSTRIES)], 'industry-grid' + (' industry-grid--dark' if dark else ''))


def process(steps, dark=False):
    return container([container([heading('0%d' % (i + 1), 'div', 'step-no'), heading(t, 'h3'), text(d)], 'step rv' + (' d%d' % (i + 1) if i else ''))
                      for i, (t, d) in enumerate(steps)], 'process' + (' process--dark' if dark else ''))


def range_card(i, key, ico, title, body, url, body_is_list=False):
    kids = [heading(title, 'h3')]
    kids.append(icon_list(body, 'dot-list') if body_is_list else text(body))
    kids.append(button('Get a quote', url, 'link-arrow', icon='fas fa-arrow-right'))
    return container([
        container([image(key, 'ki-card'), icon(ico, 'range-ico')], 'range-media'),
        container(kids, 'range-body'),
    ], 'range-card rv tilt d%d' % (i % 3 + 1) if i % 3 else 'range-card rv tilt')


# ---------------------------------------------------------------- HOME
def home():
    hero_visual = container([
        image('hero-machine', 'large', 'hv-card hv-main tilt tilt-lg'),
        image('box-kraft', 'ki-card', 'hv-card hv-sub'),
        html('<div class="hv-chip hv-chip-a"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg><span>Offset printing</span></div>'
             '<div class="hv-chip hv-chip-b"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16.5 9.4l-9-5.19M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16zM3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg><span>Custom packaging</span></div>'
             '<svg class="hv-badge" viewBox="0 0 120 120" width="120" height="120" aria-hidden="true"><defs><path id="hvcircle" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs><text><textPath href="#hvcircle" textLength="268" lengthAdjust="spacing">PRINT &#8226; PACK &#8226; DELIVER &#8226;</textPath></text></svg>',
             'hv-decor')
    ], 'hero-visual')
    content = container([
        heading('Offset printing · Packaging solutions', 'span', 'eyebrow eyebrow--light hero-eyebrow'),
        heading('Print that impresses.<br><span class="accent">Packaging that protects.</span>', 'h1', 'hero-title split'),
        text('K I Packaging Solutions delivers high-quality, cutting-edge printing and custom packaging from Kadawatha, with the commitment and faster turnaround your business deserves.', 'hero-lead'),
        container([
            button('Get a Quotation', QUOTE, 'btn-primary btn-lg ki-quote magnetic', icon='fas fa-arrow-right'),
            button('Explore services', '/printing-services/', 'btn-ghost btn-lg'),
        ], 'hero-actions', row=True),
        check_circle_list(['Outstanding quality', 'Latest technologies', 'Faster turnaround'], 'hero-points'),
    ], 'hero-content')
    decor = html('<span class="hero-shade" aria-hidden="true"></span><span class="hero-grid-lines" aria-hidden="true"></span><span class="shape shape-a" aria-hidden="true"></span><span class="shape shape-b" aria-hidden="true"></span>', 'hero-decor')
    cue = html('<a class="scroll-cue" href="#intro" aria-label="Scroll to content"><span></span></a>', 'hero-cue')
    gallery = [{'id': MEDIA[k][0], 'url': MEDIA[k][1]} for k in ('press-detail', 'press-roll', 'open-boxes')]
    hero = container([decor, container([content, hero_visual], 'container hero-inner', row=True), cue], 'hero ki-hero', tag='section',
                     background_background='slideshow', background_slideshow_gallery=gallery, background_slideshow_loop='yes',
                     background_slideshow_slide_duration=6500, background_slideshow_slide_transition='fade',
                     background_slideshow_transition_duration=1500, background_slideshow_ken_burns='yes',
                     background_slideshow_ken_burns_zoom_direction='in', background_slideshow_background_size='cover',
                     background_slideshow_background_position='center center')

    marquee = container([icon_list(['Hospitals', 'Healthcare', 'Home Appliance', 'Food & Beverages', 'Resorts & Spa', 'Cosmetics & Soaps', 'Ayurvedic',
                                    'Gherkin Industries'] + SECTORS, 'marquee-list', 'fas fa-star')], 'marquee')

    intro = section([container([
        container([
            image('press-rollers', 'large', 'intro-img intro-img-a rv-img'),
            image('machine-sorkz', 'ki-card', 'intro-img intro-img-b rv-img d2'),
            container([heading('17+', 'div', 'ki-count'), text('industries served')], 'intro-badge'),
        ], 'intro-media rv-left'),
        container([
            heading('Who we are', 'span', 'eyebrow'),
            heading('A collective group of people trying to make a difference in print.', 'h2', 'h2'),
            text('Welcome to K I Packaging Solutions. We offer high-quality, cutting-edge printing services to our customers. Commitment, dedication and customer satisfaction are the key components of our philosophy.', 'lead'),
            text('We provide solutions to the practical problems that can come up during a job, and we maintain an extensive digital library of logos and products. It is how we have won the satisfaction and trust of our customers.'),
            icon_list(['Individualised solutions for every client', 'Extensive digital library of logos and products', 'Results that count, job after job'], 'check-list', 'fas fa-check'),
            button('More about us', '/about-us/', 'btn-dark', icon='fas fa-arrow-right'),
        ], 'intro-copy rv'),
    ], 'split-2')], cls='intro-section', first=False)
    intro['settings']['_element_id'] = 'intro'

    stats = container([container([
        container([heading('17+', 'div', 'ki-count'), text('Industries served')], 'stat rv'),
        container([heading('30+', 'div', 'ki-count'), text('Print & packaging products')], 'stat rv d1'),
        container([heading('4', 'div', 'ki-count'), text('Core values we work by')], 'stat rv d2'),
    ], 'container stats-grid')], 'stats', tag='section')

    services = section([
        head('What we do', 'Two disciplines. One dependable partner.', 'From a single brochure to thousands of cartons, we handle printing and packaging under one roof.'),
        container([
            container([image('brochure-display', 'ki-wide', 'svc-media'), container([
                icon('fas fa-print', 'svc-ico'), heading('Printing Services', 'h3'),
                text('Offset and commercial printing for corporate stationery, brochures, catalogues, books, posters, calendars and more, produced on our Heidelberg presses.'),
                icon_list(['Stationery', 'Brochures', 'Catalogues', 'Books', 'Posters'], 'chip-row'),
                button('View printing services', '/printing-services/', 'link-arrow', icon='fas fa-arrow-right'),
            ], 'svc-body')], 'svc-card rv tilt'),
            container([image('boxes-labelled', 'ki-wide', 'svc-media'), container([
                icon('fas fa-box-open', 'svc-ico'), heading('Packaging Solutions', 'h3'),
                text('Cartons, E & B fluted boxes, PVC boxes, labels, stickers, carry bags and garment accessories made to fit your product and your brand.'),
                icon_list(['Cartons', 'Fluted boxes', 'Labels', 'Carry bags', 'Hang tags'], 'chip-row'),
                button('View packaging solutions', '/packaging-solutions/', 'link-arrow', icon='fas fa-arrow-right'),
            ], 'svc-body')], 'svc-card rv tilt d2'),
        ], 'svc-duo'),
    ], kind='section--paper')

    products = section([
        container([
            container([heading('Our products', 'span', 'eyebrow'), heading('Made to order, made to impress.', 'h2', 'h2')], 'split-head-copy'),
            button('View all products', '/our-products/', 'btn-outline', icon='fas fa-arrow-right'),
        ], 'section-head split-head rv', row=True),
        shortcode('[ki_products limit="8" featured="1" cols="4"]'),
    ])

    values = section([head('Why K I Packaging', 'Four values behind every job.', 'We will be judged not only by our words but by our actions.', light=True), value_cards()],
                     kind='section--dark values-section')
    values['elements'].insert(0, html('<span class="glow glow-a" aria-hidden="true"></span>', 'hero-decor'))

    machines = section([head('Our production floor', 'Serious machinery behind every print.',
                             'Precision presses, cutters and die-cutting machines keep quality consistent from the first sheet to the last.'), machine_cards()])

    industries = section([head('Industries', 'Trusted across many industries.', 'Packaging and print for hospitals, hotels, manufacturers and brands of every size.'),
                          industry_grid(), icon_list(SECTORS, 'chip-row chip-row--center rv')], kind='section--paper')

    how = section([head('How it works', 'From brief to delivery in four steps.'),
                   process([('Share your brief', 'Tell us what you need, the quantity and your timeline through our quotation form.'),
                            ('Quotation & proof', 'We confirm the details, send your quotation and prepare a proof for approval.'),
                            ('Print & produce', 'Your job runs on our offset presses and finishing machines with quality checks throughout.'),
                            ('Finish & deliver', 'Cut, folded, packed and delivered, ready to launch your product or campaign.')])])

    return [hero, marquee, intro, stats, services, products, values, machines, industries, how,
            cta_band('Ready to bring your project to life?', 'Send us your printing or packaging requirement and get a quotation from the K I Packaging team.')]


# ---------------------------------------------------------------- ABOUT
def about():
    story = section([container([
        container([
            heading('Our story', 'span', 'eyebrow'), heading('Welcome to K I Packaging Solutions.', 'h2', 'h2'),
            text('We offer high-quality and cutting-edge printing services to our customers. Commitment, dedication and customer satisfaction are the key components in our philosophy.', 'lead'),
            text('We are a collective group of people trying to make a difference in this world. We provide solutions to potential or existing practical problems which may be encountered during the job, and we maintain an extensive digital library of logos and products.'),
            text('It is this approach that has won us the satisfaction and trust of our customers, from first-time clients to long-standing business partners.'),
            button('Request a quotation', QUOTE, 'btn-primary ki-quote', icon='fas fa-arrow-right'),
        ], 'intro-copy rv'),
        container([
            image('press-vintage', 'ki-wide', 'about-img about-img-a rv-img'),
            image('press-rollers', 'ki-card', 'about-img about-img-b rv-img d2'),
            html('<span class="about-ring" aria-hidden="true"></span>', 'hero-decor'),
        ], 'about-media rv-left'),
    ], 'split-2')], first=True)

    mv = section([container([
        container([icon('fas fa-bullseye', 'mv-ico'), heading('Our Mission', 'h2'),
                   text('Our mission is to provide exceptional service and to follow through on our promises. We will strive to deliver individualised solutions to all our clients’ printing needs and add value to our clients’ businesses.'),
                   text('The K I Packaging Solutions team is focused on providing great service and forming lasting relationships with our clients. We will be judged not only by our words but by our actions.')], 'mv-card rv'),
        container([icon('far fa-eye', 'mv-ico'), heading('Our Vision', 'h2'),
                   text('K I Packaging Solutions endeavours to become recognised as the benchmark of outstanding service, not just in the printing industry but across all industries.'),
                   text('We pride ourselves on bringing results that count in everything that we set out to do, and we are constantly striving for successful outcomes in all areas of our business.')], 'mv-card rv d2'),
    ], 'mv-grid')], kind='section--dark mv-section')
    mv['elements'].insert(0, html('<span class="glow glow-a" aria-hidden="true"></span>', 'hero-decor'))

    values = section([head('Our values', 'What we stand for.'), value_cards(light=True)])
    machines = section([head('Our production floor', 'The machines behind the quality.', 'Offset presses, a programmable guillotine and a die-cutting machine let us take a job from sheet to finished product in-house.'), machine_cards()], kind='section--paper')
    sec_icons = ['fas fa-laptop', 'fas fa-university', 'fas fa-tv', 'fas fa-building', 'fas fa-car', 'fas fa-signal', 'fas fa-graduation-cap', 'fas fa-film', 'fas fa-box']
    sectors = section([head('We provide print services to', 'Sectors we work with.'),
                       container([container([icon(sec_icons[i], 'sector-ico'), heading(n, 'span', 'sector-name')], 'sector rv' + (' d%d' % (i % 3 + 1) if i % 3 else ''))
                                  for i, n in enumerate(SECTORS)], 'sector-grid')])
    return [page_hero('About K I Packaging Solutions', 'About Us', 'High-quality, cutting-edge printing and packaging, built on commitment, dedication and customer satisfaction.', 'paper-stack'),
            story, mv, values, machines, sectors,
            cta_band('Ready to bring your project to life?', 'Send us your printing or packaging requirement and get a quotation from the K I Packaging team.')]


# ---------------------------------------------------------------- PRINTING
def printing():
    intro = section([container([
        container([image('machine-sorkz', 'large', 'about-img about-img-a rv-img'), image('pantone', 'ki-card', 'about-img about-img-b rv-img d2')], 'about-media about-media--single rv-left'),
        container([
            heading('Printing done right', 'span', 'eyebrow'), heading('Sharp colour, clean finishing, dependable delivery.', 'h2', 'h2'),
            text('Our offset printing is run on Heidelberg presses and finished in-house on precise cutting and die-cutting machines.', 'lead'),
            text('Whether it is a single brochure or a full corporate identity set, we pair experienced operators with modern equipment so your colours, trims and folds come out exactly as approved.'),
            icon_list(['Offset & commercial printing', 'Programmable guillotine for exact trims', 'In-house die cutting', 'Digital library of logos and products'], 'check-list', 'fas fa-check'),
        ], 'intro-copy rv'),
    ], 'split-2')], first=True)
    groups = [
        ('envelope-kraft', 'fas fa-file-alt', 'Corporate stationery', ['Corporate stationers', 'Folders', 'File folders', 'Menu cards', 'Greeting cards']),
        ('brochure-display', 'fas fa-layer-group', 'Brochures & leaflets', ['Brochures', 'Leaflets', 'Pamphlets', 'Flyers', 'Literatures']),
        ('books-stack', 'fas fa-book', 'Catalogues & books', ['Catalogues', 'Product catalogues', 'Books', 'Annual reports', 'Newsletters']),
        ('posters-wall', 'far fa-image', 'Posters & advertising', ['Posters', 'Streamers', 'Danglers', 'Wobblers', 'POP items', 'Visual ads']),
        ('stickers', 'fas fa-tag', 'Labels & stickers', ['Stickers', 'Labels', 'Price tags']),
        ('calendar-desk', 'far fa-calendar-alt', 'Calendars & gifts', ['Calendars', 'Greeting cards', 'Carry bags']),
    ]
    rng = section([head('What we print', 'A complete range of printed products.'),
                   container([range_card(i, k, ic, t, items, q(t), True) for i, (k, ic, t, items) in enumerate(groups)], 'range-grid')], kind='section--paper')
    proc = section([head('Our process', 'A simple path from idea to print.', light=True),
                    process([('Brief', 'Share your artwork, size, stock and quantity.'), ('Proof', 'Approve the proof before anything goes on press.'),
                             ('Print', 'Offset printing with quality checks on the run.'), ('Finish', 'Cutting, die cutting and packing, ready to deliver.')], dark=True)],
                   kind='section--dark')
    proc['elements'].insert(0, html('<span class="glow glow-b" aria-hidden="true"></span>', 'hero-decor'))
    ind = section([head('Industries', 'Print for every sector.', 'IT, banking and finance, electronics, builders, automotive, telecom, education, media and product packaging all rely on us.'),
                   icon_list(SECTORS, 'chip-row chip-row--center rv')])
    return [page_hero('Offset & commercial printing', 'Printing Services', 'From corporate stationery to catalogues and books, printed sharply and finished to your specification.', 'press-detail'),
            intro, rng, proc, ind,
            cta_band('Have a printing job in mind?', 'Tell us the product, size and quantity and we will send you a quotation.')]


# ---------------------------------------------------------------- PACKAGING
def packaging():
    intro = section([container([
        container([
            heading('Packaging that works', 'span', 'eyebrow'), heading('Packaging built around your product.', 'h2', 'h2'),
            text('The right pack protects the product, sells the brand and fits the supply chain. We help you get all three.', 'lead'),
            text('From cosmetics and Ayurvedic products to food, home appliances and garments, we design and produce packaging that suits the product and the shelf. Tell us what you are packing and we will recommend the right style, board and finish.'),
            icon_list(['Custom sizes, structures and print', 'Die-cut and creased in-house', 'Suitable for retail, export and bulk'], 'check-list', 'fas fa-check'),
            button('Get a packaging quote', QUOTE, 'btn-primary ki-quote', icon='fas fa-arrow-right'),
        ], 'intro-copy rv'),
        container([image('boxes-labelled', 'ki-wide', 'about-img about-img-a rv-img'), image('cosmetic-boxes', 'ki-card', 'about-img about-img-b rv-img d2'),
                   html('<span class="about-ring" aria-hidden="true"></span>', 'hero-decor')], 'about-media rv-left'),
    ], 'split-2')], first=True)
    rng_items = [
        ('box-kraft', 'fas fa-box', 'Cartons', 'Printed cartons for retail and consumer products, cut and creased on our die-cutting machine.'),
        ('open-boxes', 'fas fa-boxes', 'E & B fluted boxes', 'Strong corrugated boxes for storage, transport and bulk packing.'),
        ('gift-box', 'fas fa-shield-alt', 'PVC boxes', 'Clear and rigid presentation boxes for gifts, accessories and premium goods.'),
        ('stickers', 'fas fa-tag', 'Labels & stickers', 'Product labels and stickers in the shapes, sizes and finishes your range needs.'),
        ('paper-bag', 'fas fa-shopping-bag', 'Carry bags', 'Branded paper carry bags that turn every purchase into a walking advertisement.'),
        ('hangtag-tee', 'fas fa-tags', 'Garment accessories', 'Hang tags, price tags, card inserts and collar supports for apparel brands.'),
    ]
    rng = section([head('Our packaging range', 'Everything you need to pack and present.'),
                   container([range_card(i, k, ic, t, d, q(t)) for i, (k, ic, t, d) in enumerate(rng_items)], 'range-grid')], kind='section--paper')
    garment = section([container([
        container([image('hangtag-textile', 'ki-card', 'gar-img gar-img-a rv-img'), image('tag-dark', 'ki-card', 'gar-img gar-img-b rv-img d2')], 'gar-media rv-left'),
        container([
            heading('For the garment industry', 'span', 'eyebrow'), heading('Finishing touches for apparel brands.', 'h2', 'h2'),
            text('Tags, inserts and supports that make a garment look finished and ready for the shelf.', 'lead'),
            check_circle_list(['Card inserts', 'Hang tags', 'Price tags & stickers', 'Back & support boards', 'Collar back support', 'Product catalogues', 'PVC boxes'], 'tick-grid'),
        ], 'intro-copy rv'),
    ], 'split-2 split-2--rev')])
    ind = section([head('Industries we pack for', 'Packaging for the products that matter.', light=True), industry_grid(dark=True)], kind='section--dark')
    ind['elements'].insert(0, html('<span class="glow glow-a" aria-hidden="true"></span>', 'hero-decor'))
    return [page_hero('Custom packaging', 'Packaging Solutions', 'Cartons, boxes, labels and bags designed to protect your product and present your brand.', 'boxes-stack'),
            intro, rng, garment, ind,
            cta_band('Need packaging made for your product?', 'Share the product, size and quantity and our team will recommend the right pack and send a quotation.')]


# ---------------------------------------------------------------- GALLERY
def gallery():
    return [page_hero('Our work & production floor', 'Gallery', 'A look at the machines, products and packaging that come out of K I Packaging Solutions.', 'pantone'),
            section([shortcode('[ki_gallery]')], first=True),
            cta_band('Like what you see?', 'Let us produce your next print or packaging project to the same standard.')]


# ---------------------------------------------------------------- CONTACT
def contact():
    info = container([
        heading('Contact details', 'span', 'eyebrow'), heading('We would love to hear from you.', 'h2', 'h2'),
        text('Visit our Kadawatha facility, call us, or send a quotation request online.', 'lead'),
        shortcode('[ki_contact_info]', 'contact-cards'),
        container([image('press-roll', 'ki-wide', 'contact-next-media'),
                   container([heading('What happens next?', 'h3'),
                              icon_list(['We review your requirement and artwork.', 'We send you a quotation and a proof.', 'You approve, and we print and deliver.'], 'next-steps')], 'contact-next-body')],
                  'contact-next rv'),
    ], 'contact-info rv-left')
    form = container([
        container([heading('Get a Quotation', 'span', 'eyebrow'), heading('Request your quotation', 'h3', 'h3'),
                   text('Fill in the details below. The more you tell us, the faster we can quote.')], 'quote-card-head'),
        shortcode('[ki_quote_form id="contact"]'),
    ], 'quote-card rv')
    form['settings']['_element_id'] = 'quote'
    grid = section([container([info, form], 'contact-grid')], first=True)
    mp = container([maps('411/C/4/1 Kirillawala, Kadawatha, Sri Lanka', 440, 15)], 'map-section rv', tag='section')
    return [page_hero('Get in touch', 'Contact Us', 'Tell us about your printing or packaging requirement and we will come back with a quotation.', 'press-vintage'), grid, mp]


# ---------------------------------------------------------------- SHOP (hero shown above the product catalogue)
def shop():
    return [page_hero('Printing & packaging catalogue', 'Our Products', 'Browse our range of printed materials and packaging, then request a quotation for the items you need.', 'pallets')]


ALL = {'home': home, 'about-us': about, 'printing-services': printing, 'packaging-solutions': packaging, 'gallery': gallery, 'contact-us': contact, 'our-products': shop}
