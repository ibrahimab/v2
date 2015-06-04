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

        sql = 'SELECT `accommodatie_id` AS `id`, `naam` AS `name`, `websites`, `wzt` AS `season`, ' \
              '`plaats_id`   AS `place_id`, `zoekvolgorde` AS `order` '                             \
              'FROM   `accommodatie` '                                                              \
              'WHERE  `tonen` = 1 '                                                                 \
              'AND    `tonenzoekformulier` = 1'

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
                'websites': row['websites'].split(','),
                'season':   row['season'],
                'place_id': row['place_id'],
                'order':    row['order']
            })

        collection.insert(data)
        return self