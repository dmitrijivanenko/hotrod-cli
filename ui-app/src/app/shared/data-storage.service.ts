import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import 'rxjs/Rx';

import {Command} from "../command/command.model";
import {CommandsService} from "../command/commands.service";
import {LoaderService} from "./loader.service";

@Injectable()
export class DataStorageService {
  constructor(
    private httpClient: HttpClient,
    private commandsService: CommandsService,
    private loader: LoaderService) {
  }

  getCommands() {
    this.loader.putLoader();
    return this.httpClient.get<Command[]>('/commands', {
      responseType: 'json'
    }).subscribe((commands:Command[]) => {
      this.commandsService.setCommands(commands);
      this.loader.hideLoader();
    })
  }


  getCommand(code:string) {
    return this.httpClient.get<Command>('/command/' + code, {
      responseType: 'json'
    })
      .map(
        (command) => {
          return command;
        }
      )
  }

  runCommand(command: Command, data) {
    return this.httpClient.post('/create/' + command.name, {
      "arguments" : data.arguments,
      "options" : data.options
    }, {
      responseType: 'json'
    })
  }
}

