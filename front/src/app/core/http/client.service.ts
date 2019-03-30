import { Injectable } from '@angular/core';
import { Client } from "../classes/models/client";
import { environment } from "../../../environments/environment";
import { HTTP_OPTIONS } from "../constants/http_options";
import { HttpClient } from "@angular/common/http";
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ClientService {

  constructor(private http: HttpClient) { }

  getClients(): Observable<Client[]> {
    const url = `${environment.apiUrl}/api/clients`;
    return this.http.get<Client[]>(url, HTTP_OPTIONS);
  }

  getClient(clientId: string): Observable<Client> {
    const url = `${environment.apiUrl}/api/clients/${clientId}`;
    return this.http.get<Client>(url, HTTP_OPTIONS);
  }

  addClient() {
    //
  }

 updateClient(client: Client) {
    //
 }

 deleteClient(client: Client) {
    //
 }

}
