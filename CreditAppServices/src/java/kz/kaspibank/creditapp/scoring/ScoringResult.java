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
public class ScoringResult {
    public enum Result{APPROVED,REJECTED,NONE};
    public enum Status{SUCCESS,ERROR};
    
    public Status status;
    public Result result;
    public String reason;
    public String errorMsg;

    public ScoringResult() {
        this.setReason("");
        this.setErrorMsg("");
    }

    public void setErrorMsg(String errorMsg) {
        this.errorMsg = errorMsg;
    }
    
    public void setStatus(Status status) {
        this.status = status;
    }

    public void setReason(String reason) {
        this.reason = reason;
    }

    public void setResult(Result result) {
        this.result = result;
    }
    
}
