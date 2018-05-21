import {Injectable} from "@angular/core";
import {Subject} from "rxjs/Rx";

@Injectable()
export class LoaderService {
  loaderChange = new Subject<boolean>();


  putLoader() {
    this.loaderChange.next(true);
  }

  hideLoader() {
    this.loaderChange.next(false);
  }
}