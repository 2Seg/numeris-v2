import { Address } from "./address";
import { Preference } from "./preference";

export class User {

  id: number;
  preferenceId: number;
  addressId: number;

  activated: boolean;
  touAccepted: boolean;
  subscriptionPaidAt: boolean;
  email: string;
  password: string;
  username: string;
  firstName: string;
  lastName: string;
  studentNumber: number;
  promotion: string;
  schoolYear: string;
  phone: string;
  nationality: string;
  birthDate: string;
  birthCity: string;
  socialInsuranceNumber: string;
  iban: string;
  bic: string;
  createdAt: string;
  updatedAt: string;

  address: Address;
  preference: Preference;
}
