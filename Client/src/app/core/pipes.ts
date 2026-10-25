import { DatePipe } from "@angular/common";
import { Pipe, PipeTransform } from "@angular/core";

@Pipe({
    name: 'dateTime'
})
export class DateTimePipe implements PipeTransform {
    transform(value: any, args?: any): any {
        if (!value) {
            return '';
        }

        if (!value.endsWith('Z')) {
            value += 'Z';
        }

        return new DatePipe('en-us').transform(new Date(value), 'yyyy-MM-dd h:mm a');
    }
}
