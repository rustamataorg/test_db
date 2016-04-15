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
import kz.kaspibank.creditapp.identity.IdentityNumber;

/**
 * REST Web Service
 *
 * @author KHAK-T430
 */
@Path("identity")
public class IdentityResource {

    @Context
    private UriInfo context;

    /**
     * Creates a new instance of IdentityResource
     */
    public IdentityResource() {
    }

    /**
     * Retrieves representation of an instance of kz.kaspibank.creditapp.ws.IdentityResource
     * @param iin
     * @return an instance of java.lang.String
     */
    @GET
    @Produces(MediaType.APPLICATION_XML)
    public String getXml() {
        //TODO return proper representation object
        throw new UnsupportedOperationException();
    }


    @PUT
    @Consumes(MediaType.APPLICATION_XML)
    public void putXml(String content) {
    }
    
    
    
    /**
     * Retrieves representation of an instance of kz.kaspibank.creditapp.ws.IdentityResource
     * @param iin
     * @return an instance of java.lang.String
     */
    @POST
    @Produces(MediaType.TEXT_PLAIN)
    @Consumes(MediaType.TEXT_PLAIN)
    @Path("/validateIIN")
    public String validateIIN (String iin){
        IdentityNumber identityNumber = new IdentityNumber(iin);
        return String.valueOf(identityNumber.isValid());
    }
}
