"""
Type model class

This class fetches all the types from the database
and imports it into the autocomplete collection.

@author     Ibrahim Abdullah <ibrahim@chalet.nl>
@package    Chalet
@subpackage Import Autocomplete
@version    0.0.2
@since      0.0.2
"""
from base import Base
import sys
class Type(Base):
    """
    @var  AUTOCOMPLETE_TYPE: Autocomplete type to be used when inserting autocomplete data
    @type AUTOCOMPLETE_TYPE: C{string}
    """
    AUTOCOMPLETE_TYPE = 'type'

    """
    Fetching types from the MySQL database and saving it for later use.
    @rtype: Type
    """
    def fetch(self):

        sql = 'SELECT `t`.`type_id` AS `id`, `t`.`accommodatie_id` AS `accommodation_id`, `t`.`naam` AS `name_nl`, `t`.`websites`, '     \
              '`t`.`naam_de` AS `name_de`, `t`.`naam_en` AS `name_en`, `t`.`naam_fr` AS `name_fr`, `a`.`naam` AS `accommodation_name`, ' \
              '`t`.`zoekvolgorde` AS `order`, `l`.`begincode` AS `code`, `p`.`plaats_id` AS `place_id`, `l`.`land_id` AS `country_id` '  \
              'FROM   `type` t '                                                                                                         \
              'INNER JOIN `accommodatie` a '                                                                                             \
              'ON (t.accommodatie_id = a.accommodatie_id) '                                                                              \
              'INNER JOIN   `plaats` p '                                                                                                 \
              'ON (a.plaats_id = p.plaats_id) '                                                                                          \
              'INNER JOIN `land` l '                                                                                                     \
              'ON (p.land_id = l.land_id) '                                                                                              \
              'WHERE  `t`.`tonen` = 1 '                                                                                                  \
              'AND    `t`.`tonenzoekformulier` = 1'

        self.adapter('mysql').execute(sql)
        self.data = self.adapter('mysql').fetchall()
        return self

    """
    Indexing the data fetched from MySQL database to mongodb

    @rtype: Type
    """
    def insert(self):

        if not self.data:
            return self

        collection = self.adapter('mongo').autocomplete
        data       = []

        for row in self.data:

            data.append({

                'type':             Type.AUTOCOMPLETE_TYPE,
                'type_id':          row['id'],
                'locales':          ['nl', 'en', 'fr', 'de'],
                'name':             {

                    'nl': row['accommodation_name'] + ' ' + row['name_nl'],
                    'en': row['accommodation_name'] + ' ' + row['name_en'],
                    'de': row['accommodation_name'] + ' ' + row['name_de'],
                    'fr': row['accommodation_name'] + ' ' + row['name_fr']
                },
                'code':             row['code'] + str(row['id']),
                'websites':         row['websites'].split(','),
                'accommodation_id': row['accommodation_id'],
                'place_id':         row['place_id'],
                'country_id':       row['country_id'],
                'order':            row['order']
            })

        collection.insert(data)
        return self