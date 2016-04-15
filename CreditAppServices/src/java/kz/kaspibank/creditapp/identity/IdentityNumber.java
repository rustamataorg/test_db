/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package kz.kaspibank.creditapp.identity;

/**
 *
 * @author KHAK-T430
 */
public class IdentityNumber {

    private String iin;

    public IdentityNumber(String iin) {
        this.iin = iin.trim();
    }

    public boolean isValid() {
        if (this.iin.length()!=12)//Длина ИИН отличается от 12 символов
            return false;
        //!Можно реализовать дополнительные проверки года, сверки года и века и т.п.   
        int total = 0;
        for (int i = 1; i < 12; i++) {//Перебираем разряды
            total += i * Integer.parseInt(String.valueOf(iin.charAt(i - 1)));//Считаем сумму произведений значения разряда на порядок
        }
        if ((total % 11) != 10) {
            //Сравнивает контрольный разряд с остатком от деления суммы на 11, если совпадёт, то ИИН корректный
            return ((total % 11) == Integer.parseInt(String.valueOf(iin.charAt(11))));
        } else {//Остаток от деления суммы на 11 равен 10, проводим дополнительную проверку
            total = 0;
            int positionWeight;
            for (int i = 1; i < 12; i++) {//Перебираем разряды
                positionWeight = (i + 2) % 11 > 0 ? (i + 2) % 11 : i + 2;//Считаем вес позиции
                total += positionWeight * Integer.parseInt(String.valueOf(iin.charAt(i - 1)));//Считаем сумму произведений значения разряда на его вес
            }
            if ((total % 11) == 10) {//Остаток от деления суммы на 11 снова равен 10, значит данный ИИН не используется
                return false;
            } else {
                return (total % 11) == Integer.parseInt(String.valueOf(iin.charAt(11)));//Сравнивает контрольный разряд с остатком от деления суммы на 11, если совпадёт, то ИИН корректный
            }
        }
    }
}
