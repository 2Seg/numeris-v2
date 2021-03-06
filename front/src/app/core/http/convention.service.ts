import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';
import { HTTP_OPTIONS } from '../constants/http_options';
import { Client } from '../classes/models/client';
import { Convention } from '../classes/models/convention';

@Injectable({
  providedIn: 'root'
})
export class ConventionService {

  constructor(private http: HttpClient) { }

  getClientConventions(client: number | Client): Observable<Convention[]> {
    const clientId = typeof client === 'number' ? client : client.id;
    const url = `${environment.apiUrl}/api/clients/${clientId}/conventions`;
    return this.http.get<Convention[]>(url, HTTP_OPTIONS);
  }

  getConvention(conventionId: number): Observable<Convention> {
    const url = `${environment.apiUrl}/api/conventions/${conventionId}`;
    return this.http.get<Convention>(url, HTTP_OPTIONS);
  }

  addClientConvention(client: number | Client, data: Convention): Observable<Convention> {
    const clientId = typeof client === 'number' ? client : client.id;
    const url = `${environment.apiUrl}/api/clients/${clientId}/conventions`;
    return this.http.post<Convention>(url, data, HTTP_OPTIONS);
  }

  updateConvention(convention: number | Convention, data: Convention): Observable<Convention> {
    const conventionId = typeof convention === 'number' ? convention : convention.id;
    const url = `${environment.apiUrl}/api/conventions/${conventionId}`;
    return this.http.put<Convention>(url, data, HTTP_OPTIONS);
  }

  deleteConvention(convention: number | Convention): Observable<Convention> {
    const conventionId = typeof convention === 'number' ? convention : convention.id;
    const url = `${environment.apiUrl}/api/conventions/${conventionId}`;
    return this.http.delete<Convention>(url, HTTP_OPTIONS);
  }
}
