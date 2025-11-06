<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Database\Seeders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use BilliftySDK\SharedResources\SDK\Database\MakeSeeder;

class CurrencySeeder extends MakeSeeder
{
	protected array $currencies = [
            ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$', 'precision' => 2, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'precision' => 2, 'is_active' => true],
            ['code' => 'GBP', 'name' => 'British Pound Sterling', 'symbol' => '£', 'precision' => 2, 'is_active' => true],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'precision' => 0, 'is_active' => true],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'precision' => 2, 'is_active' => true],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'precision' => 2, 'is_active' => true],
            ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF', 'precision' => 2, 'is_active' => true],
            ['code' => 'CNY', 'name' => 'Chinese Yuan Renminbi', 'symbol' => '¥', 'precision' => 2, 'is_active' => true],
            ['code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => 'HK$', 'precision' => 2, 'is_active' => true],
            ['code' => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => 'NZ$', 'precision' => 2, 'is_active' => true],
            ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$', 'precision' => 2, 'is_active' => true],
            ['code' => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr', 'precision' => 2, 'is_active' => true],
            ['code' => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr', 'precision' => 2, 'is_active' => true],
            ['code' => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr', 'precision' => 2, 'is_active' => true],
            ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'precision' => 2, 'is_active' => true],
            ['code' => 'KRW', 'name' => 'South Korean Won', 'symbol' => '₩', 'precision' => 0, 'is_active' => true],
            ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R', 'precision' => 2, 'is_active' => true],
            ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$', 'precision' => 2, 'is_active' => true],
            ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => '$', 'precision' => 2, 'is_active' => true],
            ['code' => 'PHP', 'name' => 'Philippine Peso', 'symbol' => '₱', 'precision' => 2, 'is_active' => true],
            ['code' => 'THB', 'name' => 'Thai Baht', 'symbol' => '฿', 'precision' => 2, 'is_active' => true],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ', 'precision' => 2, 'is_active' => true],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼', 'precision' => 2, 'is_active' => true],
            ['code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺', 'precision' => 2, 'is_active' => true],
            ['code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽', 'precision' => 2, 'is_active' => true],
            ['code' => 'PLN', 'name' => 'Polish Zloty', 'symbol' => 'zł', 'precision' => 2, 'is_active' => true],
            ['code' => 'HUF', 'name' => 'Hungarian Forint', 'symbol' => 'Ft', 'precision' => 0, 'is_active' => true],
            ['code' => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč', 'precision' => 2, 'is_active' => true],
            ['code' => 'ILS', 'name' => 'Israeli Shekel', 'symbol' => '₪', 'precision' => 2, 'is_active' => true],
            ['code' => 'MYR', 'name' => 'Malaysian Ringgit', 'symbol' => 'RM', 'precision' => 2, 'is_active' => true],
            ['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp', 'precision' => 0, 'is_active' => true],
            ['code' => 'VND', 'name' => 'Vietnamese Dong', 'symbol' => '₫', 'precision' => 0, 'is_active' => true],
            ['code' => 'PKR', 'name' => 'Pakistani Rupee', 'symbol' => '₨', 'precision' => 2, 'is_active' => true],
            ['code' => 'BDT', 'name' => 'Bangladeshi Taka', 'symbol' => '৳', 'precision' => 2, 'is_active' => true],
            ['code' => 'NGN', 'name' => 'Nigerian Naira', 'symbol' => '₦', 'precision' => 2, 'is_active' => true],
            ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => '£', 'precision' => 2, 'is_active' => true],
            ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'precision' => 2, 'is_active' => true],
            ['code' => 'GHS', 'name' => 'Ghanaian Cedi', 'symbol' => '₵', 'precision' => 2, 'is_active' => true],
            ['code' => 'CLP', 'name' => 'Chilean Peso', 'symbol' => '$', 'precision' => 0, 'is_active' => true],
            ['code' => 'ARS', 'name' => 'Argentine Peso', 'symbol' => '$', 'precision' => 2, 'is_active' => true],
            ['code' => 'COP', 'name' => 'Colombian Peso', 'symbol' => '$', 'precision' => 2, 'is_active' => true],
            ['code' => 'PEN', 'name' => 'Peruvian Sol', 'symbol' => 'S/', 'precision' => 2, 'is_active' => true],
            ['code' => 'UYU', 'name' => 'Uruguayan Peso', 'symbol' => '$U', 'precision' => 2, 'is_active' => true],
            ['code' => 'TWD', 'name' => 'New Taiwan Dollar', 'symbol' => 'NT$', 'precision' => 2, 'is_active' => true],
            ['code' => 'QAR', 'name' => 'Qatari Riyal', 'symbol' => '﷼', 'precision' => 2, 'is_active' => true],
            ['code' => 'BHD', 'name' => 'Bahraini Dinar', 'symbol' => '.د.ب', 'precision' => 3, 'is_active' => true],
            ['code' => 'OMR', 'name' => 'Omani Rial', 'symbol' => '﷼', 'precision' => 3, 'is_active' => true],
            ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك', 'precision' => 3, 'is_active' => true],
            ['code' => 'LKR', 'name' => 'Sri Lankan Rupee', 'symbol' => 'Rs', 'precision' => 2, 'is_active' => true],
            ['code' => 'MMK', 'name' => 'Myanmar Kyat', 'symbol' => 'K', 'precision' => 0, 'is_active' => true],
            ['code' => 'NPR', 'name' => 'Nepalese Rupee', 'symbol' => '₨', 'precision' => 2, 'is_active' => true],
            ['code' => 'BND', 'name' => 'Brunei Dollar', 'symbol' => 'B$', 'precision' => 2, 'is_active' => true],
            ['code' => 'LAK', 'name' => 'Lao Kip', 'symbol' => '₭', 'precision' => 2, 'is_active' => true],
            ['code' => 'KHR', 'name' => 'Cambodian Riel', 'symbol' => '៛', 'precision' => 2, 'is_active' => true],
            ['code' => 'MOP', 'name' => 'Macanese Pataca', 'symbol' => 'MOP$', 'precision' => 2, 'is_active' => true],
            ['code' => 'BMD', 'name' => 'Bermudian Dollar', 'symbol' => '$', 'precision' => 2, 'is_active' => true],
            ['code' => 'JMD', 'name' => 'Jamaican Dollar', 'symbol' => 'J$', 'precision' => 2, 'is_active' => true],
            ['code' => 'TTD', 'name' => 'Trinidad and Tobago Dollar', 'symbol' => 'TT$', 'precision' => 2, 'is_active' => true],
            ['code' => 'BBD', 'name' => 'Barbadian Dollar', 'symbol' => 'Bds$', 'precision' => 2, 'is_active' => true],
            ['code' => 'XOF', 'name' => 'West African CFA Franc', 'symbol' => 'CFA', 'precision' => 0, 'is_active' => true],
            ['code' => 'XAF', 'name' => 'Central African CFA Franc', 'symbol' => 'FCFA', 'precision' => 0, 'is_active' => true],
            ['code' => 'MUR', 'name' => 'Mauritian Rupee', 'symbol' => '₨', 'precision' => 2, 'is_active' => true],
            ['code' => 'SCR', 'name' => 'Seychellois Rupee', 'symbol' => '₨', 'precision' => 2, 'is_active' => true],
            ['code' => 'TZS', 'name' => 'Tanzanian Shilling', 'symbol' => 'TSh', 'precision' => 2, 'is_active' => true],
            ['code' => 'UGX', 'name' => 'Ugandan Shilling', 'symbol' => 'USh', 'precision' => 0, 'is_active' => true],
        ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		foreach($this->currencies as $currency) {
			Currency::updateOrCreate($currency);
		}
    }

    /**
     * Revert the database seeds.
     */
    public function revert(): void
    {
		Currency::truncate();
    }
}
