<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\News;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'email' => 'admin@eldesco.am',
            'name' => 'Admin',
            'password_hash' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Services
        Service::create([
            'title_hy' => 'Ցածր և միջին լարման Էներգետիկ ենթակառուցվածքներ',
            'title_en' => 'Low- and Medium-Voltage Power Infrastructure',
            'title_ru' => 'Низкое и среднее напряжение',
            'description_hy' => 'Միջին լարման ենթակայաններ, բաշխիչ վահանակներ և հաղորդադեղերի ամբողջական համակարգեր',
            'description_en' => 'Medium-voltage substations, distribution panels, and complete power transmission systems',
            'description_ru' => 'Подстанции среднего напряжения, распределительные панели и полные системы передачи электроэнергии',
            'icon' => '⚡',
            'order_index' => 1
        ]);

        Service::create([
            'title_hy' => 'Արտադրական ենթակառուցվածքներ',
            'title_en' => 'Industrial Production Infrastructure',
            'title_ru' => 'Производственная инфраструктура',
            'description_hy' => 'Սեղմած օդի կայաններ, պոմպակայաններ, SCADA ավտոմատացված համակարգեր',
            'description_en' => 'Compressed air stations, pumping stations, and SCADA automated control systems',
            'description_ru' => 'Станции сжатого воздуха, насосные станции и автоматизированные системы SCADA',
            'icon' => '🏭',
            'order_index' => 2
        ]);

        Service::create([
            'title_hy' => 'Լուսադիոդային էկրաններ',
            'title_en' => 'LED Display Systems',
            'title_ru' => 'Системы светодиодных дисплеев',
            'description_hy' => 'Բարձր ճշգրտության LED էկրաններ ցանկացած չափի և տեղադրման համար',
            'description_en' => 'High-precision LED displays of any size and configuration',
            'description_ru' => 'Высокоточные светодиодные дисплеи любого размера',
            'icon' => '📺',
            'order_index' => 3
        ]);

        Service::create([
            'title_hy' => 'Սառնարանային սարքավորումներ',
            'title_en' => 'Refrigeration Equipment',
            'title_ru' => 'Холодильное оборудование',
            'description_hy' => 'Ժամանակակից սառնարանային համակարգեր և սարքավորումներ ամբողջ շրջանակ',
            'description_en' => 'Modern refrigeration systems and equipment for all applications',
            'description_ru' => 'Современные системы охлаждения для всех приложений',
            'icon' => '❄️',
            'order_index' => 4
        ]);

        Service::create([
            'title_hy' => 'Թիթեղային մետաղի մշակում',
            'title_en' => 'Sheet Metal Processing',
            'title_ru' => 'Обработка листового металла',
            'description_hy' => 'Ամբողջական ցիկլ թիթեղային մետաղի մշակման համար բարձր ճշգրտության սարքավորումներ',
            'description_en' => 'Full-cycle sheet metal processing with precision machinery',
            'description_ru' => 'Полный цикл обработки листового металла на современном оборудовании',
            'icon' => '🔧',
            'order_index' => 5
        ]);

        // Projects/Case Studies
        Project::create([
            'title_hy' => '3200 կՎ հզորությամբ միջին լարման ենթակայաններ',
            'title_en' => 'Medium Voltage Substations - 3200 kW Capacity',
            'title_ru' => 'Подстанции среднего напряжения мощностью 3200 кВ',
            'description_hy' => 'Պրոֆեսիոնալ մեծ հզորության ենթակայաններ տեղադրված մեծ արտադրական հաստիքներում',
            'description_en' => 'Professional high-capacity substations installed in major industrial facilities',
            'description_ru' => 'Профессиональные высокомощные подстанции, установленные на крупных промышленных объектах',
            'category' => 'Infrastructure',
            'featured' => true,
            'order_index' => 1
        ]);

        Project::create([
            'title_hy' => 'Սեղմած օդի մատակարարման համակարգեր',
            'title_en' => 'Compressed Air Supply Systems',
            'title_ru' => 'Системы подачи сжатого воздуха',
            'description_hy' => 'Մեծ ծավալի օդի մատակարարման համակարգեր խողովակաշարի ամբողջական հավաքածուով',
            'description_en' => 'Large-volume air supply systems with complete pipeline infrastructure',
            'description_ru' => 'Системы подачи воздуха большого объема с полной инфраструктурой трубопроводов',
            'category' => 'Systems',
            'featured' => true,
            'order_index' => 2
        ]);

        Project::create([
            'title_hy' => 'SCADA ավտոմատացված կառավարման համակարգեր',
            'title_en' => 'SCADA Automation Control Systems',
            'title_ru' => 'Автоматизированные системы управления SCADA',
            'description_hy' => 'Siemens STEP 7-ի վրա հիմնված հեռավար վերահսկման ամբողջական համակարգեր',
            'description_en' => 'Complete remote monitoring and control systems based on Siemens STEP 7',
            'description_ru' => 'Полные системы удаленного мониторинга и управления на базе Siemens STEP 7',
            'category' => 'Automation',
            'featured' => false,
            'order_index' => 3
        ]);

        Project::create([
            'title_hy' => 'LED ցուցադրման պլատֆորմեր',
            'title_en' => 'LED Display Platforms',
            'title_ru' => 'Платформы светодиодных дисплеев',
            'description_hy' => 'Մեծ մասշտաբի LED էկրաններ օդրենի տարածքներում և պետական հաստիքներում',
            'description_en' => 'Large-scale LED screens installed in outdoor spaces and public facilities',
            'description_ru' => 'Крупномасштабные светодиодные экраны, установленные на открытых пространствах',
            'category' => 'Display',
            'featured' => true,
            'order_index' => 4
        ]);

        Project::create([
            'title_hy' => 'Սառնարանային կազմերի ներդրում',
            'title_en' => 'Refrigeration System Implementation',
            'title_ru' => 'Внедрение систем охлаждения',
            'description_hy' => 'Բարձր տեխնոլոգիայի սառնարանային համակարգեր խոշոր հաստիքներում',
            'description_en' => 'High-tech refrigeration systems in large-scale industrial facilities',
            'description_ru' => 'Высокотехнологичные системы охлаждения в крупных промышленных объектах',
            'category' => 'Equipment',
            'featured' => false,
            'order_index' => 5
        ]);

        // Team Members
        TeamMember::create([
            'name_hy' => 'Վահե Պարսամյան',
            'name_en' => 'Vahe Parsamyan',
            'name_ru' => 'Ваге Парсамян',
            'position_hy' => 'Տնօրեն',
            'position_en' => 'CEO/Director',
            'position_ru' => 'Генеральный директор',
            'email' => 'vahe@eldesco.am',
            'order_index' => 1
        ]);

        TeamMember::create([
            'name_hy' => 'Վահրամ Կիկոյան',
            'name_en' => 'Vahram Kikoyan',
            'name_ru' => 'Варам Киkoян',
            'position_hy' => 'Գլխավոր հաշվապահ',
            'position_en' => 'Chief Accountant',
            'position_ru' => 'Главный бухгалтер',
            'email' => 'vahram@eldesco.am',
            'order_index' => 2
        ]);

        // News/Blog
        News::create([
            'title_hy' => 'ELDESCO LLC-ի մասին',
            'title_en' => 'About ELDESCO LLC',
            'title_ru' => 'О компании ELDESCO LLC',
            'slug_hy' => 'eldesco-mashin',
            'slug_en' => 'about-eldesco',
            'slug_ru' => 'o-eldesco',
            'content_hy' => 'ELDESCO LLC հիմնադրվել է 2011թ-ին: Գործունեության ծավալման ընթացում մեր ընկերությունը համագործակցել է հարյուրավոր փոքր, միջին, ինչպես նաև խոշոր տնտեսավարողների հետ, ինչը դարձել է շարունակական՝ մատուցված ծառայության բարձր որակի շնորհիվ:',
            'content_en' => 'ELDESCO LLC was established in 2011. Throughout its operational growth, the company has partnered with hundreds of small, medium, and large enterprises, building long-term collaborations driven by the consistently high quality of its services.',
            'content_ru' => 'Компания ELDESCO LLC была основана в 2011 году. За время своего развития компания сотрудничала с сотнями малых, средних и крупных предприятий, создавая долгосрочные партнерства благодаря высокому качеству предоставляемых услуг.',
            'excerpt_hy' => 'ELDESCO LLC - բարձր որակի ինժեներական լուծումների տնտեսավար',
            'excerpt_en' => 'ELDESCO LLC - provider of high-quality engineering solutions',
            'excerpt_ru' => 'ELDESCO LLC - поставщик высококачественных инженерных решений',
            'published' => true,
        ]);

        News::create([
            'title_hy' => 'Նոր մետաղամշակման բաժնի հիմնում',
            'title_en' => 'Launch of New Metal Processing Division',
            'title_ru' => 'Запуск нового отделения обработки металла',
            'slug_hy' => 'metal-baghin-himnum',
            'slug_en' => 'metal-division-launch',
            'slug_ru' => 'zapusk-odelenia-metalla',
            'content_hy' => 'Արտադրական հնարավորությունների ընդլայնման նպատակով 2023 թվականին ընկերությունը հիմնեց մետաղամշակման առանձին ուղղություն (Metalworks LLC): Կարճ ժամանակահատվածում այն դիրքավորվեց որպես ոլորտի առաջատարներից մեկը Հայաստանի Հանրապետությունում:',
            'content_en' => 'In 2023, with the aim of expanding its production capabilities, the company established a dedicated metal processing division (Metalworks LLC). Within a short period, it positioned itself among the industry leaders in the Republic of Armenia.',
            'content_ru' => 'В 2023 году с целью расширения производственных возможностей компания открыла отдельное отделение обработки металла (Metalworks LLC). За короткий период оно позиционировало себя как один из лидеров отрасли в Республике Армения.',
            'excerpt_hy' => 'Նոր ուղղություն մետաղամշակման ոլորտում',
            'excerpt_en' => 'New direction in metal processing',
            'excerpt_ru' => 'Новое направление в обработке металла',
            'published' => true,
        ]);

        News::create([
            'title_hy' => 'Գիտահետազոտական ուղղության ծայտանում',
            'title_en' => 'Research & Development Division Expansion',
            'title_ru' => 'Расширение отдела НИОКР',
            'slug_hy' => 'gitahatazocakan',
            'slug_en' => 'research-development',
            'slug_ru' => 'nauchnie-razrabotki',
            'content_hy' => 'ELDESCO LLC-ի առանձնակի ուղղություններից մեկն է գիտահետազոտական ուղղությունը՝ որը առաջարկում է մեծ չափերի լազերային բյուրեղների, արհեստական թանկարժեք քարերի արտադրություն:',
            'content_en' => 'One of ELDESCO LLC\'s distinctive directions is its research and development division, specializing in the production of large-size laser crystals and synthetic precious stones.',
            'content_ru' => 'Одно из отличительных направлений деятельности ELDESCO LLC - отдел научных исследований и разработок, специализирующийся на производстве лазерных кристаллов большого размера и синтетических драгоценных камней.',
            'excerpt_hy' => 'Նոր գիտական հայտնագործություններ',
            'excerpt_en' => 'New scientific innovations',
            'excerpt_ru' => 'Новые научные инновации',
            'published' => true,
        ]);

        // Gallery - using relative paths for images (assuming they'll be uploaded)
        for ($i = 0; $i < 5; $i++) {
            Gallery::create([
                'title_hy' => 'Ենթակայաններ և հաղորդադեղեր',
                'title_en' => 'Substations and Infrastructure',
                'title_ru' => 'Подстанции и инфраструктура',
                'image_url' => '/images/projects/substation-' . ($i + 1) . '.jpg',
                'thumbnail_url' => '/images/projects/thumb-substation-' . ($i + 1) . '.jpg',
                'category' => 'Infrastructure',
                'order_index' => $i + 1
            ]);
        }

        for ($i = 0; $i < 4; $i++) {
            Gallery::create([
                'title_hy' => 'LED էկրաններ',
                'title_en' => 'LED Displays',
                'title_ru' => 'LED Дисплеи',
                'image_url' => '/images/projects/led-' . ($i + 1) . '.jpg',
                'thumbnail_url' => '/images/projects/thumb-led-' . ($i + 1) . '.jpg',
                'category' => 'LED',
                'order_index' => $i + 5
            ]);
        }

        for ($i = 0; $i < 3; $i++) {
            Gallery::create([
                'title_hy' => 'Մետաղամշակում',
                'title_en' => 'Metal Processing',
                'title_ru' => 'Обработка металла',
                'image_url' => '/images/projects/metalworks-' . ($i + 1) . '.jpg',
                'thumbnail_url' => '/images/projects/thumb-metalworks-' . ($i + 1) . '.jpg',
                'category' => 'Metalworks',
                'order_index' => $i + 9
            ]);
        }
    }
}
