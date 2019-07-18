import moment from 'moment';
import { dateFormat } from '../constants/date';

export function formatDate( date: string, format: string = dateFormat ): Date
{
    return moment( date, format ).toDate();
}

export function formatDateFrom( date: string, oldFormat: string, newFormat: string ): string
{
    return moment( date, oldFormat ).format( newFormat );
}
