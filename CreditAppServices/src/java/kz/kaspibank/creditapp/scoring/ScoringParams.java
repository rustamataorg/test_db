/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package kz.kaspibank.creditapp.scoring;

/**
 *
 * @author KHAK-T430
 */
public class ScoringParams {
    public float amount;//Запрошенная сумма
    public int period;//Cрок в месяцах
    public int rate;//Cтавка кредитования
    public int salary;//Заработная плата (в месяц в тенге)
    public int spendings;//Ежемесячные расходы

    public float getAmount() {
        return amount;
    }

    public int getPeriod() {
        return period;
    }

    public int getRate() {
        return rate;
    }

    public int getSalary() {
        return salary;
    }

    public int getSpendings() {
        return spendings;
    }
}
