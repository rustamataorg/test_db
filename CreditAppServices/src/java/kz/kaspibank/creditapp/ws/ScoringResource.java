/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package kz.kaspibank.creditapp.ws;

import javax.ws.rs.core.Context;
import javax.ws.rs.core.UriInfo;
import javax.ws.rs.Consumes;
import javax.ws.rs.Produces;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PUT;
import javax.ws.rs.core.MediaType;
import kz.kaspibank.creditapp.scoring.Scoring;
import kz.kaspibank.creditapp.scoring.ScoringParams;
import kz.kaspibank.creditapp.scoring.ScoringResult;

/**
 * REST Web Service
 *
 * @author KHAK-T430
 */
@Path("scoring")
public class ScoringResource {

    @Context
    private UriInfo context;

    /**
     * Creates a new instance of ScoringResource
     */
    public ScoringResource() {
    }

    /**
     * Retrieves representation of an instance of kz.kaspibank.creditapp.ws.ScoringResource
     * @return an instance of java.lang.String
     */
    @GET
    @Produces(MediaType.APPLICATION_XML)
    public String getXml() {
        //TODO return proper representation object
        throw new UnsupportedOperationException();
    }

    /**
     * PUT method for updating or creating an instance of ScoringResource
     * @param content representation for the resource
     */
    @PUT
    @Consumes(MediaType.APPLICATION_XML)
    public void putXml(String content) {
    }
    
    @POST
    @Produces(MediaType.APPLICATION_JSON)
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/requestCreditApproval")
    public ScoringResult requestCreditApproval (ScoringParams params){
        Scoring scoring = new Scoring();
        return scoring.requestCreditApproval(params);
    }
    
}
