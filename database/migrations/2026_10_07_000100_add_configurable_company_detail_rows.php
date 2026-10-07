<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = [
            ['is_visible' => true, 'label' => ['hy' => 'Կազմակերպություն', 'en' => 'Company', 'ru' => 'Организация'], 'source' => 'company.name', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'ՀՎՀՀ', 'en' => 'Tax ID', 'ru' => 'ИНН'], 'source' => 'company.tax_id', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Պետ. գրանցման համար', 'en' => 'Registration number', 'ru' => 'Регистрационный номер'], 'source' => 'company.registration', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Գործնական հասցե', 'en' => 'Business address', 'ru' => 'Фактический адрес'], 'source' => 'contact.business_address', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Իրավ. հասցե', 'en' => 'Legal address', 'ru' => 'Юридический адрес'], 'source' => 'contact.legal_address', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Բանկ', 'en' => 'Bank', 'ru' => 'Банк'], 'source' => 'bank.name', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Հաշվարկային հաշիվ (AMD)', 'en' => 'Bank account (AMD)', 'ru' => 'Расчётный счёт (AMD)'], 'source' => 'bank.account_amd', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Հաշվարկային հաշիվ (USD)', 'en' => 'Bank account (USD)', 'ru' => 'Расчётный счёт (USD)'], 'source' => 'bank.account_usd', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Հաշվարկային հաշիվ (EUR)', 'en' => 'Bank account (EUR)', 'ru' => 'Расчётный счёт (EUR)'], 'source' => 'bank.account_eur', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Հեռախոս', 'en' => 'Phone', 'ru' => 'Телефон'], 'source' => 'contact.phone', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Էլ. փոստ', 'en' => 'Email', 'ru' => 'Эл. почта'], 'source' => 'contact.email', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Տնօրեն', 'en' => 'Director', 'ru' => 'Директор'], 'source' => 'company.director', 'value' => $this->emptyLocalized()],
            ['is_visible' => true, 'label' => ['hy' => 'Հաշվապահ', 'en' => 'Chief accountant', 'ru' => 'Главный бухгалтер'], 'source' => 'company.chief_accountant', 'value' => $this->emptyLocalized()],
        ];

        DB::table('page_sections')
            ->where('type', 'company_details')
            ->orderBy('id')
            ->eachById(function ($section) use ($rows): void {
                $content = json_decode($section->content ?: '{}', true) ?: [];

                if (array_key_exists('rows', $content)) {
                    return;
                }

                $content['rows'] = $rows;
                DB::table('page_sections')->where('id', $section->id)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        DB::table('page_sections')
            ->where('type', 'company_details')
            ->orderBy('id')
            ->eachById(function ($section): void {
                $content = json_decode($section->content ?: '{}', true) ?: [];
                unset($content['rows']);
                DB::table('page_sections')->where('id', $section->id)->update([
                    'content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'updated_at' => now(),
                ]);
            });
    }

    private function emptyLocalized(): array
    {
        return ['hy' => '', 'en' => '', 'ru' => ''];
    }
};
