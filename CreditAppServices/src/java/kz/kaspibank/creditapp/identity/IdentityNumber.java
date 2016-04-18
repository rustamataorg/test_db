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

    public IdentityValidationResult isValid() {
        IdentityValidationResult identityValidationResult = new IdentityValidationResult();

        try {
            if (this.iin.length() != 12)//Длина ИИН отличается от 12 символов
            {
                identityValidationResult.setValidation(IdentityValidationResult.Validation.INVALID);
                identityValidationResult.setStatus(IdentityValidationResult.Status.SUCCESS);
                return identityValidationResult;
            }
            int total = 0;
            for (int i = 1; i < 12; i++) {//Перебираем разряды
                total += i * Integer.parseInt(String.valueOf(iin.charAt(i - 1)));//Считаем сумму произведений значения разряда на порядок
            }
            if ((total % 11) != 10) {
                //Сравнивает контрольный разряд с остатком от деления суммы на 11, если совпадёт, то ИИН корректный
                if ((total % 11) == Integer.parseInt(String.valueOf(iin.charAt(11)))) {
                    identityValidationResult.setValidation(IdentityValidationResult.Validation.VALID);
                } else {
                    identityValidationResult.setValidation(IdentityValidationResult.Validation.INVALID);
                }
                identityValidationResult.setStatus(IdentityValidationResult.Status.SUCCESS);
                return identityValidationResult;
            } else {//Остаток от деления суммы на 11 равен 10, проводим дополнительную проверку
                total = 0;
                int positionWeight;
                for (int i = 1; i < 12; i++) {//Перебираем разряды
                    positionWeight = (i + 2) % 11 > 0 ? (i + 2) % 11 : i + 2;//Считаем вес позиции
                    total += positionWeight * Integer.parseInt(String.valueOf(iin.charAt(i - 1)));//Считаем сумму произведений значения разряда на его вес
                }
                if ((total % 11) == 10) {//Остаток от деления суммы на 11 снова равен 10, значит данный ИИН не используется
                    identityValidationResult.setValidation(IdentityValidationResult.Validation.INVALID);
                    identityValidationResult.setStatus(IdentityValidationResult.Status.SUCCESS);
                    return identityValidationResult;
                } else {
                    if ((total % 11) == Integer.parseInt(String.valueOf(iin.charAt(11)))) {//Сравнивает контрольный разряд с остатком от деления суммы на 11, если совпадёт, то ИИН корректный
                        identityValidationResult.setValidation(IdentityValidationResult.Validation.VALID);
                    } else {
                        identityValidationResult.setValidation(IdentityValidationResult.Validation.INVALID);
                    }
                    identityValidationResult.setStatus(IdentityValidationResult.Status.SUCCESS);
                    return identityValidationResult;
                }
            }
        } catch (Exception ex) {
            identityValidationResult.setValidation(IdentityValidationResult.Validation.NONE);
            identityValidationResult.setStatus(IdentityValidationResult.Status.ERROR);
            identityValidationResult.setErrorMsg(ex.getMessage());
            return identityValidationResult;
        }
    }
}
