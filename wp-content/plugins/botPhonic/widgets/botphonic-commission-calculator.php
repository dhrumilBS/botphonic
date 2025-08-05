<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Botphonic_Commission_Calculator extends Widget_Base
{

    public function get_name()
    {
        return 'botphonic_commission_calculator';
    }

    public function get_title()
    {
        return __('Commission Calculator', 'botphonic');
    }

    public function get_icon()
    {
        return 'eicon-number-field';
    }

    public function get_categories()
    {
        return ['botphonic-widgets'];
    }
    public function get_style_depends()
    {
        return ['botphonic-commission'];
    }

    // public function get_script_depends()
    // {
    //     return ['jquery', 'botphonic-tabs-js'];
    // }
    public function render()
    {
?>
        <section class="calculator-section">
            <div class="commission-header">
                <span>Affiliate Commission <strong>25%</strong></span>
            </div>

            <div class="commission-display">
                <div class="commission-amount">
                    <h1 id="monthlyCommission">$0.00</h1>
                    <p>Your Monthly Commission</p>
                </div>
                <div class="accounts-count">
                    <h1 id="accountsNumber">0</h1>
                    <p>Botphonic AI Assistant Accounts</p>
                </div>
            </div>

            <div class="slider-container">
                <input
                    type="range"
                    id="accountSlider"
                    min="0"
                    max="100"
                    value="0"
                    step="1"
                    list="tickmarks" />
                <datalist id="tickmarks">
                    <?php for ($i = 0; $i <= 100; $i += 5): ?>
                        <option value="<?= $i ?>"></option>
                    <?php endfor; ?>
                </datalist>

                <div class="slider-labels-wrapper">
                    <div class="slider-labels">
                        <?php for ($i = 0; $i <= 100; $i += 5): ?>
                            <span><?= $i ?></span>
                        <?php endfor; ?>
                    </div>
                </div>

                <p class="slider-title">Botphonic AI Assistant Accounts</p>
            </div>
        </section>

        <style>
            <?php include plugin_dir_path(__FILE__) . 'commission-style.css'; ?>
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const slider = document.getElementById("accountSlider");
                const commissionLabel = document.getElementById("monthlyCommission");
                const accountLabel = document.getElementById("accountsNumber");
                const COMMISSION_PERCENT = 0.25;
                const PLAN_PRICE = 100;

                slider.addEventListener("input", function() {
                    const accounts = parseInt(this.value);
                    const totalCommission = accounts * PLAN_PRICE * COMMISSION_PERCENT;
                    commissionLabel.textContent = `$${totalCommission.toFixed(2)}`;
                    accountLabel.textContent = accounts;
                });
            });
        </script>
<?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Botphonic_Commission_Calculator());