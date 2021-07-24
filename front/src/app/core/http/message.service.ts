import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Message } from '../classes/models/message';
import { HTTP_OPTIONS } from '../constants/http_options';
import { environment } from '../../../environments/environment';
import { PaginatedMessage } from '../classes/pagination/paginated-message';

@Injectable({
  providedIn: 'root'
})
export class MessageService {

  constructor(private http: HttpClient) { }

  getMessages(): Observable<PaginatedMessage[]> {
    const url = `${environment.apiUrl}/api/messages`;
    return this.http.get<PaginatedMessage[]>(url, HTTP_OPTIONS);
  }

  getCurrentMessage(): Observable<Message> {
    const url = `${environment.apiUrl}/api/messages/current`;
    return this.http.get<Message>(url, HTTP_OPTIONS);
  }

  addMessage(data: Message): Observable<Message> {
      const url = `${environment.apiUrl}/api/messages`;
      return this.http.post<Message>(url,data,HTTP_OPTIONS);
  }

  updateMessage(data: Message, message: Message | number): Observable<Message> {
    const messageId: number = typeof message === 'number' ? message : message.id;
    const url = `${environment.apiUrl}/api/messages/${messageId}`;
    return this.http.put<Message>(url, data, HTTP_OPTIONS);
  }

  updateMessageActivated(activation: boolean, message: Message | number): Observable<Message> {
    const messageId: number = typeof message === 'number' ? message : message.id;
    const url = `${environment.apiUrl}/api/messages/${messageId}/activation`;
    return this.http.patch<Message>(url, { activation: activation }, HTTP_OPTIONS);
  }

  deleteMessage(message: Message): Observable<Message> {
    const messageId: number = typeof message === 'number' ? message : message.id;
    const url = `${environment.apiUrl}/api/messages/${messageId}`;
    return this.http.delete<Message>(url, HTTP_OPTIONS);
  }


}
