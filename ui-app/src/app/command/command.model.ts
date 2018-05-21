import { Option } from '../shared/option.model';
import { Argument } from "../shared/argument.model";

export class Command {
  public name: string;
  public description: string;
  public help: string;
  public options: Option[];
  public arguments: Argument[];

  constructor(name: string, desc: string) {
    this.name = name;
    this.description = desc;
  }
}
