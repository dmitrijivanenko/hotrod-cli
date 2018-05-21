import {Component, OnInit} from '@angular/core';
import {DataStorageService} from "./shared/data-storage.service";
import {LoaderService} from "./shared/loader.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit{
  title = 'app';

  showLoader:boolean = false;

  constructor(private dataService: DataStorageService, private loader: LoaderService) {
  }

  ngOnInit() {
    this.loader.loaderChange.subscribe(result => {
      this.showLoader = result;
    });
    this.dataService.getCommands();
  }
}
