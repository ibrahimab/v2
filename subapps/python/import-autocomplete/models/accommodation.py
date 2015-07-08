"""
Accommodation model class

This class fetches all the accommodations from the database
that are searchable and imports it into autocomplete collection in MongoDB.

@author     Ibrahim Abdullah <ibrahim@chalet.nl>
@package    Chalet
@subpackage Import Autocomplete
@version    0.0.2
@since      0.0.2
"""
from base import Base
import sys
class Accommodation(Base):
    """
    @var  AUTOCOMPLETE_TYPE: Autocomplete type to be used when inserting autocomplete data
    @type AUTOCOMPLETE_TYPE: C{string}
    """
    AUTOCOMPLETE_TYPE = 'accommodation'

    """
    Fetching accommodations from the MySQL database and saving it for later use.
    @rtype: Accommodation
    """
    def fetch(self):

        sql = 'SELECT DISTINCT `a`.`accommodatie_id` AS `id`, `a`.`naam` AS `name`, `a`.`websites`, `a`.`wzt` AS `season`, ' \
              '`a`.`plaats_id` AS `place_id`, `zoekvolgorde` AS `order`, `l`.`begincode` AS `code` '                         \
              'FROM   `accommodatie` a '                                                                                     \
              'INNER JOIN   `plaats` p '                                                                                     \
              'ON (a.plaats_id = p.plaats_id) '                                                                              \
              'INNER JOIN `land` l '                                                                                         \
              'ON (p.land_id = l.land_id) '                                                                                  \
              'WHERE  `a`.`tonen` = 1 '                                                                                      \
              'AND    `a`.`tonenzoekformulier` = 1'

        self.adapter('mysql').execute(sql)
        self.data = self.adapter('mysql').fetchall()

        return self

    """
    Indexing the data fetched from MySQL database to mongodb

    @rtype: Accommodation
    """
    def insert(self):

        if not self.data:
            return self

        collection = self.adapter('mongo').autocomplete
        data       = []

        for row in self.data:

            data.append({

                'type':     Accommodation.AUTOCOMPLETE_TYPE,
                'type_id':  row['id'],
                'locales':  None,
                'name':     row['name'],
                'code':     row['code'] + str(row['id']),
                'websites': row['websites'].split(','),
                'season':   row['season'],
                'place_id': row['place_id'],
                'order':    row['order']
            })

        collection.insert(data)
        return self