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

        sql = "SELECT DISTINCT `accommodatie_id` AS `id`, `naam` AS `name`, `begincode` AS `code` " \
              "FROM   `view_accommodatie` "                                                         \
              "WHERE FIND_IN_SET('%(website)s', `websites`) > 0 "                                   \
              "AND `atonen` = 1 "                                                                   \
              "AND `ttonen` = 1 "                                                                   \
              "AND `atonenzoekformulier` = 1 "                                                      \
              "AND `ttonenzoekformulier` = 1 "                                                      \
              "AND `archief` = 0 "                                                                  \
              "AND `weekendski` = 0 "                                                               \
              "ORDER BY `naam` ASC"

        self.adapter('mysql').execute(sql % {'website': self.website})
        self.data = self.adapter('mysql').fetchall()

        return self

    """
    Indexing the data fetched from MySQL database to mongodb

    @rtype: Accommodation
    """
    def insert(self):

        if not self.data:
            return self

        collection = self.collection()
        data       = []
        order      = 0

        for row in self.data:

            data.append({

                'type': Accommodation.AUTOCOMPLETE_TYPE,
                'type_id': row['id'],
                'locales': None,
                'name': row['name'],
                'searchable': row['name'].lower() if isinstance(row['name'], basestring) else row['name'],
                'order': order
            })
            
            order += 1

        collection.insert(data)
        return self