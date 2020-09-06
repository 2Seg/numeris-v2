import { Component, Input, OnInit } from '@angular/core';
import { Message } from '../../../../core/classes/models/message';
import { MessageService } from '../../../../core/http/message.service';
import { Moment } from 'moment';
import * as moment from 'moment';
import { Router } from '@angular/router';

@Component({
  selector: 'app-message',
  templateUrl: './message.component.html',
  styleUrls: ['./message.component.css']
})
export class MessageComponent implements OnInit {

  messages: Message[];
  loading: boolean = false;
  currentmessage: Message;

  constructor(
      private messageService: MessageService,
  ) { }

  ngOnInit() {
    this.getCurrentMessage();
    this.getAllMessages();
  }

  getCurrentMessage() {
    this.loading = true;

    this.messageService.getCurrentMessage().subscribe(currentmessage =>{
      this.currentmessage=currentmessage;

      this.loading =false;
    });
  }

  getAllMessages(){
    this.loading = true;
    this.messageService.getMessages().subscribe(messages =>{
      this.messages = messages;

      this.loading=false;
    })
  }


}
