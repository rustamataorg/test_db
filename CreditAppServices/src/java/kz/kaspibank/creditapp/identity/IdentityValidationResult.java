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
public class IdentityValidationResult {

    public enum Status {
        SUCCESS, ERROR
    };

    public enum Validation {
        VALID, INVALID, NONE
    };
    
    public Validation validation;
    public Status status;
    public String errorMsg;

    public IdentityValidationResult() {
        this.setErrorMsg("");
    }

    public void setStatus(Status status) {
        this.status = status;
    }

    public void setErrorMsg(String errorMsg) {
        this.errorMsg = errorMsg;
    }

    public void setValidation(Validation validation) {
        this.validation = validation;
    }
    
}