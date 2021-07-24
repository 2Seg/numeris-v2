import { Message } from "../models/message";
import { Link } from "./link";
import { Meta } from "./meta";
import { IPagination } from "./pagination-interface";

export class PaginatedMessage implements IPagination {

  data: Message[];
  links: Link;
  meta: Meta;

}
