import {Injectable} from "@angular/core";
import {Command} from "./command.model";
import {Subject} from "rxjs/Rx";

@Injectable()
export class CommandsService {

  commandsChanged = new Subject<Command[]>();

  private commands: Command[];

  constructor() {
  }

  setCommands(commands: Command[]) {
    this.commands = commands;
    this.commandsChanged.next(this.commands);
  }

  getCommands() {
    return this.commands;
  }

  getCommand(code:string) {
    if (this.commands) {
      return this.commands[code];
    }

    return null;
  }

}