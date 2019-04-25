import { Component, Input, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { Client } from "../../../../core/classes/models/client";
import { ClientService } from "../../../../core/http/client.service";
import { first } from "rxjs/operators";
import { Router } from "@angular/router";
import { handleFormErrors } from "../../../../core/functions/form-error-handler";

@Component({
  selector: 'app-client-form',
  templateUrl: './client-form.component.html'
})
export class ClientFormComponent implements OnInit {

  @Input() client: Client;

  clientForm: FormGroup;
  loading: boolean = false;
  submitted: boolean = false;

  constructor(
    private fb: FormBuilder,
    private clientService: ClientService,
    private router: Router,
  ) { }

  ngOnInit() {
    this.clientForm = this.fb.group({
      name: [
        this.client ? this.client.name : '',
        Validators.required,
      ],
      reference: [
        this.client ? this.client.reference : '',
        Validators.required,
      ],
      address: this.fb.group({
        street: [
          this.client ? this.client.address.street : '',
          Validators.required,
        ],
        zip_code: [
          this.client ? this.client.address.zipCode : '',
          Validators.required,
        ],
        city: [
          this.client ? this.client.address.city : '',
          Validators.required,
        ],
      })
    });
  }

  get f() { return this.clientForm.controls; }

  fa(field: string) { return this.clientForm.get(`address.${field}`); }

  onSubmit() {
    this.submitted = true;

    if (this.clientForm.invalid) return;

    this.loading = true;
    this.clientService.addClient(this.clientForm.value as Client)
      .pipe(first())
      .subscribe(
        client => {
          this.loading = false;
          this.router.navigate([`/clients/${client.id}`]);
        },
        errors => {
          handleFormErrors(this.clientForm, errors);

          this.loading = false;
        }
      );
  }

}
