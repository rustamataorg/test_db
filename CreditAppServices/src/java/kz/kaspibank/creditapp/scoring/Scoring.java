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
public class Scoring extends ScoringParams {

    public ScoringResult requestCreditApproval(ScoringParams scoringInfo) {
        ScoringResult scoringResult = new ScoringResult();

        try {
            float percentPayment = scoringInfo.getAmount() * scoringInfo.getRate() / 12 / 100;
            float debtPayment=scoringInfo.getAmount()/scoringInfo.getPeriod();
            int freeIncome = scoringInfo.getSalary() - scoringInfo.getSpendings();

            if ((percentPayment + debtPayment) > (0.75 * freeIncome)) {
                scoringResult.setResult(ScoringResult.Result.REJECTED);
                scoringResult.setStatus(ScoringResult.Status.SUCCESS);
                scoringResult.setReason("Ежемесячных остаточных средств недостаточно для выплаты");
            } else {
                scoringResult.setResult(ScoringResult.Result.APPROVED);
                scoringResult.setStatus(ScoringResult.Status.SUCCESS);
            }
        } catch (Exception ex) {
            scoringResult.setResult(ScoringResult.Result.NONE);
            scoringResult.setStatus(ScoringResult.Status.ERROR);
            scoringResult.setErrorMsg(ex.getMessage());
        }

        return scoringResult;
    }
}
