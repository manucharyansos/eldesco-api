<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@eldesco.am')],
            [
                'name' => 'ELDESCO Admin',
                'password_hash' => Hash::make(env('ADMIN_PASSWORD', 'please-change-this-password')),
                'role' => 'admin',
            ]
        );

        $this->seedSettings();
        $this->seedPages();
    }

    private function seedSettings(): void
    {
        $settings = [
            ['key' => 'site.name', 'group' => 'site', 'value' => 'ELDESCO', 'is_public' => true],
            ['key' => 'site.tagline', 'group' => 'site', 'value' => $this->t('Էներգետիկ ենթակառուցվածքների և ինժեներական համակարգերի նախագծում և պատրաստում', 'Design and manufacturing of energy infrastructure and engineering systems'), 'is_public' => true],
            ['key' => 'site.primary_color', 'group' => 'appearance', 'value' => '#0B2947', 'is_public' => true],
            ['key' => 'site.accent_color', 'group' => 'appearance', 'value' => '#C85A2B', 'is_public' => true],
            ['key' => 'contact.address', 'group' => 'contact', 'value' => $this->t('ՀՀ, ք. Երևան, Թբիլիսյան 35/9', '35/9 Tbilisyan Hwy, Yerevan, Armenia'), 'is_public' => true],
            ['key' => 'contact.phone', 'group' => 'contact', 'value' => '+37499694569', 'is_public' => true],
            ['key' => 'contact.email', 'group' => 'contact', 'value' => 'eldesco@eldesco.am', 'is_public' => true],
            ['key' => 'company.founded', 'group' => 'company', 'value' => '2011', 'is_public' => true],
            ['key' => 'company.legal_name', 'group' => 'company', 'value' => $this->t('ԷԼԴԵՍՔՈ ՍՊԸ', 'ELDESCO LLC'), 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }

    private function seedPages(): void
    {
        $activityItems = [
            $this->item('power', 'Ցածր և միջին լարման էներգետիկ ենթակառուցվածքներ', 'Low- and Medium-Voltage Power Infrastructure Solutions', '/power-infrastructure'),
            $this->item('industrial', 'Արտադրական ենթակառուցվածքներ', 'Industrial Production Infrastructure', '/industrial-infrastructure'),
            $this->item('led', 'Լուսադիոդային էկրաններ', 'LED Display Systems', '/led-displays'),
            $this->item('cooling', 'Սառնարանային սարքավորումներ', 'Refrigeration and Cooling Equipment', '/refrigeration'),
            $this->item('metal', 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ', 'Full-Cycle Sheet Metal Processing', '/sheet-metal'),
        ];

        $this->page('home', 'Գլխավոր', 'Home', 0, true, [
            $this->section('hero', 'hero', 'Էներգետիկ ենթակառուցվածքների և ինժեներական համակարգերի նախագծում և պատրաստում', 'Design and manufacturing of energy infrastructure and engineering systems', 'Էլդեսքո ՍՊԸ-ն հիմնադրվել է 2011թ-ին։ Մենք իրականացնում ենք էներգետիկ և արտադրական ենթակառուցվածքների նախագծում, պատրաստում և ներդրում։', 'ELDESCO LLC was established in 2011. We design, manufacture and implement energy and industrial infrastructure solutions.'),
            $this->section('activities', 'cards', 'Գործունեության ոլորտները', 'Areas of activity', null, null, $activityItems),
            $this->section('quality', 'rich_text', 'Փորձ և որակ', 'Experience and quality', 'Գործունեության ծավալման ընթացքում ընկերությունը համագործակցել է հարյուրավոր փոքր, միջին և խոշոր տնտեսավարողների հետ՝ ձևավորելով շարունակական համագործակցություն մատուցված ծառայությունների բարձր որակի շնորհիվ։', 'Throughout its operational growth, the company has partnered with hundreds of small, medium and large enterprises, building long-term collaborations driven by the consistently high quality of its services.'),
            $this->section('customers', 'logos', 'Մեր պատվիրատուները', 'Our customers', null, null, [
                $this->item('apache', 'Apache', 'Apache'),
                $this->item('dalma', 'Dalma Garden Mall', 'Dalma Garden Mall'),
                $this->item('solar-city', 'Solar City', 'Solar City'),
                $this->item('teghout', 'Teghout', 'Teghout'),
                $this->item('viva-mts', 'Viva-MTS', 'Viva-MTS'),
                $this->item('veon', 'VEON', 'VEON'),
                $this->item('team', 'Team Telecom Armenia', 'Team Telecom Armenia'),
                $this->item('armenia-wine', 'Armenia Wine', 'Armenia Wine'),
                $this->item('synopsys', 'Synopsys', 'Synopsys'),
                $this->item('synergy', 'Synergy International Systems', 'Synergy International Systems'),
                $this->item('dilijan', 'Dilijan Beer', 'Dilijan Beer'),
                $this->item('astra', 'Astra Crystals', 'Astra Crystals'),
            ]),
            $this->section('contact-cta', 'cta', 'Քննարկենք ձեր նախագիծը', 'Let’s discuss your project', 'Կապվեք մեզ հետ՝ տեխնիկական առաջադրանքը քննարկելու համար։', 'Contact us to discuss your technical requirements.', [], ['button' => $this->t('Կապ մեզ հետ', 'Contact us'), 'href' => '/contact']),
        ]);

        $this->page('about', 'Մեր մասին', 'About', 1, true, [
            $this->section('hero', 'hero', 'ԷլԴեսՔո-ի մասին', 'About ELDESCO', 'Էլդեսքո ՍՊԸ-ն հիմնադրվել է 2011թ-ին։', 'ELDESCO LLC was established in 2011.'),
            $this->section('history', 'rich_text', 'Մեր փորձը', 'Our experience', 'Գործունեության ծավալման ընթացքում մեր ընկերությունը համագործակցել է հարյուրավոր փոքր, միջին, ինչպես նաև խոշոր տնտեսավարողների հետ, ինչը դարձել է շարունակական՝ մատուցված ծառայության բարձր որակի շնորհիվ։', 'Throughout its operational growth, the company has partnered with hundreds of small, medium, and large enterprises, building long-term collaborations driven by the consistently high quality of its services.'),
            $this->section('metalworks', 'rich_text', 'Մետաղամշակման ուղղություն', 'Metal processing division', 'Արտադրական հնարավորությունների ընդլայնման նպատակով 2023 թվականին ընկերությունը հիմնեց մետաղամշակման առանձին ուղղություն՝ «Մեթալորքս» ՍՊԸ։ Արտասահմանյան գործընկերների հետ համագործակցության արդյունքում ընկերությունը համալրվել է մետաղի մշակման ժամանակակից, բարձր ճշգրտության սարքավորումներով, որոնց մի մասը եզակի է ՀՀ-ում։', 'In 2023, with the aim of expanding its production capabilities, the company established a dedicated metal processing division, Metalworks LLC. Through cooperation with international partners, the company has been equipped with advanced, high-precision metal processing machinery, some of which is unique within Armenia.'),
            $this->section('research', 'rich_text', 'Գիտահետազոտական ուղղություն', 'Research and development', 'Էլդեսքո ՍՊԸ-ի առանձնակի ուղղություններից մեկն է գիտահետազոտական ուղղությունը, որը առաջարկում է մեծ չափերի լազերային բյուրեղների և արհեստական թանկարժեք քարերի արտադրություն։', 'One of ELDESCO LLC’s distinctive directions is its research and development division, specializing in the production of large-size laser crystals and synthetic precious stones.'),
        ]);

        $this->page('activities', 'Գործունեություն', 'Activities', 2, true, [
            $this->section('hero', 'hero', 'Գործունեության ոլորտները', 'Areas of activity'),
            $this->section('activity-list', 'cards', 'Ինժեներական լուծումներ', 'Engineering solutions', null, null, $activityItems),
        ]);

        $this->page('contact', 'Կապ', 'Contact', 3, true, [
            $this->section('hero', 'hero', 'Կապ մեզ հետ', 'Contact us'),
            $this->section('contact', 'contact', 'Կոնտակտային տվյալներ', 'Contact information', 'ՀՀ, ք. Երևան, Թբիլիսյան 35/9\n+374 99 694569\neldesco@eldesco.am', '35/9 Tbilisyan Hwy, Yerevan, Armenia\n+374 99 694569\neldesco@eldesco.am'),
        ]);

        $this->page('power-infrastructure', 'Էներգետիկ ենթակառուցվածքներ', 'Power Infrastructure', 100, false, [
            $this->section('hero', 'hero', 'Ցածր և միջին լարման էներգետիկ ենթակառուցվածքներ', 'Low- and Medium-Voltage Power Infrastructure'),
            $this->section('services', 'list', 'Լուծումներ', 'Solutions', null, null, [
                $this->item('substations', 'Միջին լարման ենթակայաններ', 'Medium-voltage substations'),
                $this->item('panels', 'Ցածր և միջին լարման բաշխիչ վահանակներ', 'Low- and medium-voltage distribution panels'),
                $this->item('transmission', 'Ցածր լարման էներգիայի տեղափոխում (հաղորդադողեր, մալուխներ)', 'Low-voltage power transmission systems (busbars and cable networks)'),
                $this->item('converters', 'Ցածր և միջին լարման հաճախային փոխակերպիչներ, շարժիչի փափուկ գործարկման համակարգեր (մոնտաժ)', 'Installation of low- and medium-voltage frequency converters and motor soft starter systems'),
            ]),
            $this->section('gallery', 'gallery', 'Կատարված աշխատանքներ', 'Selected work'),
        ]);

        $this->page('industrial-infrastructure', 'Արտադրական ենթակառուցվածքներ', 'Industrial Infrastructure', 101, false, [
            $this->section('hero', 'hero', 'Արտադրական ենթակառուցվածքներ', 'Industrial Production Infrastructure Solutions'),
            $this->section('services', 'list', 'Լուծումներ', 'Solutions', null, null, [
                $this->item('air', 'Սեղմած օդի մատակարարման կայաններ, խողովակաշարեր', 'Compressed air supply stations and pipeline systems'),
                $this->item('pumps', 'Պոմպակայաններ, չժ պողպատից խողովակաշար', 'Pumping stations and stainless steel pipeline systems'),
                $this->item('scada', 'SCADA՝ Siemens STEP 7-ի հիման վրա', 'SCADA automated systems based on the Siemens STEP 7 platform'),
            ]),
            $this->section('gallery', 'gallery', 'Կատարված աշխատանքներ', 'Selected work'),
        ]);

        $this->page('refrigeration', 'Սառնարանային սարքավորումներ', 'Refrigeration and Cooling Equipment', 102, false, [
            $this->section('hero', 'hero', 'Սառնարանային սարքավորումներ', 'Refrigeration and Cooling Equipment'),
            $this->section('gallery', 'gallery', 'Կատարված աշխատանքներ', 'Selected work'),
        ]);

        $this->page('led-displays', 'Լուսադիոդային էկրաններ', 'LED Display Systems', 103, false, [
            $this->section('hero', 'hero', 'Լուսադիոդային էկրաններ', 'LED Display Systems'),
            $this->section('gallery', 'gallery', 'Կատարված աշխատանքներ', 'Selected work'),
        ]);

        $this->page('sheet-metal', 'Թիթեղային մետաղի մշակում', 'Sheet Metal Manufacturing', 104, false, [
            $this->section('hero', 'hero', 'Թիթեղային մետաղի մշակման ամբողջական ցիկլ', 'Full-Cycle Sheet Metal Manufacturing'),
            $this->section('gallery', 'gallery', 'Արտադրական հնարավորություններ', 'Manufacturing capabilities'),
        ]);
    }

    private function page(string $slug, string $hy, string $en, int $sortOrder, bool $showInNav, array $sections): void
    {
        $page = Page::updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $this->t($hy, $en),
                'seo' => [
                    'title' => $this->t($hy.' | ELDESCO', $en.' | ELDESCO'),
                    'description' => $this->t('ELDESCO ինժեներական և էներգետիկ լուծումներ', 'ELDESCO engineering and energy infrastructure solutions'),
                ],
                'is_published' => true,
                'show_in_nav' => $showInNav,
                'sort_order' => $sortOrder,
            ]
        );

        $page->sections()->delete();
        foreach ($sections as $sectionIndex => $data) {
            $items = $data['items'] ?? [];
            unset($data['items']);
            $section = $page->sections()->create($data + ['sort_order' => $sectionIndex]);

            foreach ($items as $itemIndex => $item) {
                $section->items()->create($item + ['sort_order' => $itemIndex]);
            }
        }
    }

    private function section(string $key, string $type, string $titleHy, string $titleEn, ?string $bodyHy = null, ?string $bodyEn = null, array $items = [], array $settings = []): array
    {
        return [
            'key' => $key,
            'type' => $type,
            'title' => $this->t($titleHy, $titleEn),
            'body' => ($bodyHy !== null || $bodyEn !== null) ? $this->t($bodyHy ?? '', $bodyEn ?? '') : null,
            'settings' => $settings,
            'is_enabled' => true,
            'items' => $items,
        ];
    }

    private function item(string $key, string $hy, string $en, ?string $link = null): array
    {
        return [
            'key' => $key,
            'title' => $this->t($hy, $en),
            'link_url' => $link,
            'is_enabled' => true,
        ];
    }

    private function t(string $hy, string $en): array
    {
        return ['hy' => $hy, 'en' => $en, 'ru' => ''];
    }
}
