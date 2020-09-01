import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ParametersComponent } from './parameters/parameters.component';
import { SuiModule } from 'ng2-semantic-ui';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ManagementRoutingModule } from './management-routing.module';
import { SharedModule } from '../../shared/shared.module';
import { HttpClientModule } from '@angular/common/http';
import { ParametersModule } from './parameters/parameters.module';
import { MessageComponent } from './parameters/message/message.component';


@NgModule({
  imports: [
    CommonModule,
    SuiModule,
    FormsModule,
    ReactiveFormsModule,
    SharedModule,
    HttpClientModule,
    ManagementRoutingModule,

    // MUST be the last imports
  ],
  declarations: [
    ParametersComponent,
    MessageComponent,
  ]
})
export class ManagementModule { }
